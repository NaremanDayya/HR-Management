<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use App\Services\EmployeeViewDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    protected EmployeeViewDataService $dropdownService;

    public function __construct(EmployeeViewDataService $dropdownService)
    {
        $this->dropdownService = $dropdownService;
    }

    public function index(Request $request)
    {
        $roles = Role::with('permissions')
            ->whereNotIn('name', ['admin', 'shelf_stacker', 'area_manager', 'supervisor'])
            ->get();

        $permissions = Permission::all()->groupBy('group');

        $accountStatus = $request->query('account_status');
        $managedProjectIds = Project::all();
        $employeesQuery = Employee::with('user');
        $user = Auth::user();
        if ($user->role === 'project_manager') {
            $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');
            $employeesQuery->whereIn('project_id', $managedProjectIds);
        }

        $employees = $employeesQuery->get();

        if ($accountStatus === 'active') {
            $employees = $employees->filter(fn($e) => $e->user?->account_status === 'active');
        } elseif ($accountStatus === 'inactive') {
            $employees = $employees->filter(fn($e) => $e->user?->account_status === 'inactive');
        }

        $statistics = [
            'nationalities' => $this->getNationalitiesStats($employees),
            'ageGroups' => $this->getAgeGroupsStats($employees),
            'activeness' => $this->getActivenessStats($employees),
            'salaries' => $this->getSalariesStats($employees),

            'employees_count' => $employees->count(),
            'active_count' => $employees->filter(fn($e) => $e->user?->account_status === 'active')->count(),
            'inactive_count' => $employees->filter(fn($e) => $e->user?->account_status === 'inactive')->count(),
            'with_health_card' => $employees->where('health_card', 1)->count(),
            'without_health_card' => $employees->where('health_card', 0)->count(),
            'nationalities_count' => $employees
                ->pluck('user')
                ->filter()
                ->pluck('nationality')
                ->unique()
                ->count(),
            'managedProjectIds' => $managedProjectIds->count(),
            'role_counts' => [
                'project_manager' => $employees->filter(fn($emp) => $emp->user?->role === 'project_manager')->count(),
                'area_manager' => $employees->filter(fn($emp) => $emp->user?->role === 'area_manager')->count(),
                'shelf_stacker' => $employees->filter(fn($emp) => $emp->user?->role === 'shelf_stacker')->count(),
                'supervisor' => $employees->filter(fn($emp) => $emp->user?->role === 'supervisor')->count(),
            ],

            'hr_manager_name' => User::where('role', 'hr_manager')->value('name'),
            'hr_assistant_name' => User::where('role', 'hr_assistant')->value('name'),
            'admin_name' => User::where('role', 'admin')->value('name'),
        ];

        return view('dashboard', compact('roles', 'permissions', 'statistics', 'accountStatus'), $this->dropdownService->getDropdownData());
    }


    private function getActivenessStats($employees)
    {
        $totalEmployees = $employees->count();
        $activeEmployees = $employees->filter(fn($e) => $e->stop_reason === null)->count();

        return [
            'active' => $totalEmployees > 0 ? round(($activeEmployees / $totalEmployees) * 100, 2) : 0,
            'inactive' => $totalEmployees > 0 ? round((($totalEmployees - $activeEmployees) / $totalEmployees) * 100, 2) : 0
        ];
    }

    private function getSalariesStats($employees)
    {
        $salaries = $employees->pluck('salary')->filter();

        return [
            'total' => $salaries->sum(),
            'average' => $salaries->avg(),
            'min' => $salaries->min(),
            'max' => $salaries->max()
        ];
    }

    private function getNationalitiesStats($employees)
    {
        return $employees->pluck('user')
            ->filter()
            ->groupBy('nationality')
            ->map->count()
            ->sortDesc();
    }
    private function getAgeGroupsStats($employees)
    {
        $ageGroups = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '45+' => 0,
            'غير محدد' => 0
        ];

        foreach ($employees as $employee) {
            if (!$employee->user || !$employee->user->birthday) {
                $ageGroups['غير محدد']++;
                continue;
            }

            $age = Carbon::parse($employee->user->birthday)->age;

            if ($age >= 18 && $age <= 25) $ageGroups['18-25']++;
            elseif ($age >= 26 && $age <= 35) $ageGroups['26-35']++;
            elseif ($age >= 36 && $age <= 45) $ageGroups['36-45']++;
            elseif ($age > 45) $ageGroups['45+']++;
            else $ageGroups['غير محدد']++;
        }

        return collect($ageGroups);
    }



    public function edit(Role $role)
    {
        $permissions = Permission::all();

        return view('admin.roles.edit-permissions', compact('role', 'permissions'));
    }

    public function update(Request $request, $roleId)
    {
        try {

            $role = Role::findOrFail($roleId);

            $validated = $request->validate([
                'permissions' => 'sometimes|array',
                'permissions.*' => 'string|exists:permissions,name'
            ]);

            $role->syncPermissions($validated['permissions'] ?? []);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الصلاحيات بنجاح'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
    public function reports(Request $request)
    {
        $roles = Role::with('permissions')
            ->whereIn('name', ['project_manager', 'hr_manager', 'hr_assistant'])
            ->get();

        $permissions = Permission::all()->groupBy('group');

        $accountStatus = $request->query('account_status');

        $employeesQuery = Employee::with('user');

        if (Auth::user()->role === 'project_manager') {
            $managedProjectIds = Project::where('manager_id', Auth::id())->pluck('id');
            $employeesQuery->whereIn('project_id', $managedProjectIds);
        }

        $employees = $employeesQuery->get();

        if ($accountStatus === 'active') {
            $employees = $employees->filter(fn($e) => $e->user?->account_status === 'active');
        } elseif ($accountStatus === 'inactive') {
            $employees = $employees->filter(fn($e) => $e->user?->account_status === 'inactive');
        }

        $statistics = [
            'nationalities' => $this->getNationalitiesStats($employees),
            'ageGroups' => $this->getAgeGroupsStats($employees),
            'activeness' => $this->getActivenessStats($employees),
            'salaries' => $this->getSalariesStats($employees),
            'employees_count' => $employees->count(),
            'active_count' => $employees->filter(fn($e) => $e->user?->account_status === 'active')->count(),
            'inactive_count' => $employees->filter(fn($e) => $e->user?->account_status === 'inactive')->count(),
            'with_health_card' => $employees->where('health_card', 1)->count(),
            'without_health_card' => $employees->where('health_card', 0)->count(),
            'nationalities_count' => $employees->pluck('user')->filter()->pluck('nationality')->unique()->count(),
            'role_counts' => [
                'project_manager' => $employees->filter(fn($emp) => $emp->user?->role === 'project_manager')->count(),
                'area_manager' => $employees->filter(fn($emp) => $emp->user?->role === 'area_manager')->count(),
                'shelf_stacker' => $employees->filter(fn($emp) => $emp->user?->role === 'shelf_stacker')->count(),
                'supervisor' => $employees->filter(fn($emp) => $emp->user?->role === 'supervisor')->count(),
            ],
            'hr_manager_name' => User::where('role', 'hr_manager')->value('name'),
            'hr_assistant_name' => User::where('role', 'hr_assistant')->value('name'),
            'admin_name' => User::where('role', 'admin')->value('name'),
        ];

        return view('Employees.reports', compact('roles', 'permissions', 'statistics', 'accountStatus'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح.');
    }
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'personal_image' => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($user->personal_image && Storage::exists('public/' . $user->personal_image)) {
            Storage::delete('public/' . $user->personal_image);
        }

        $path = $request->file('personal_image')->store('employees/images', 'public');

        $user->personal_image = $path;
        $user->save();

        return back()->with('success', 'تم تغيير الصورة الشخصية بنجاح.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);
        $role->load('permissions');
        return response()->json([
            'message' => 'تم إضافة الدور بنجاح',
            'role' => $role
        ]);
    }
}
