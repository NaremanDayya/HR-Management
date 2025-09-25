<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplaceEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Advance;
use App\Models\AdvanceDeduction;
use App\Models\Alert;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeEditRequest;
use App\Models\EmployeeReplacement;
use App\Models\Project;
use App\Models\SalaryIncrease;
use App\Models\TemporaryProjectAssignment;
use App\Models\User;
use App\Notifications\NewEmployeeNotification;
use App\Services\EmployeeService;
use App\Services\EmployeeViewDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;
    protected EmployeeViewDataService $dropdownService;

    public function __construct(EmployeeService $employeeService, EmployeeViewDataService $dropdownService)
    {
        $this->employeeService = $employeeService;
        $this->dropdownService = $dropdownService;
    }
    public function index(Request $request)
    {
        // dd(Auth::user()->role);
        $filters = $request->only([
            'search',
            'account_status',
            'project',
            'marital_status',
            'english_level',
            'residence',
            'residence_neighborhood',
            'black_list',
            'role',
            'health_card',
            'nationality',
        ]);

        $employees = $this->employeeService->filterEmployees($filters);
        $resources = EmployeeResource::collection($employees);
        // dd($resources);
        // dd($resources->firstWhere('id', 45)->toArray(request()));
        // $employee2 = $resources->firstWhere('id', 45);
        // dd($employee2->managedProjects);
        $projectsObjects = Project::all();
        $supervisors = Employee::whereHas('user', function ($query) {
            $query->where('role', 'supervisor');
        })
            ->select('id', 'name', 'project_id')
            ->get();
        $area_managers = Employee::whereHas('user', function ($query) {
            $query->where('role', 'area_manager');
        })
            ->select('id', 'name', 'project_id')
            ->get();
        return view('Employees.table', array_merge([
            'employees' => $resources->toArray(request()),
            'projectsObjects' => $projectsObjects,
            'supervisors' => $supervisors,
            'area_managers' => $area_managers,
            'totalSalaries' => number_format($employees->sum('salary')),
            'employeesCount' => $employees->count(),
            'avgSalaries' => number_format($employees->avg('salary')),
            'minSalaries' => number_format($employees->min('salary')),
            'maxSalaries' => number_format($employees->max('salary')),
            'authRole' => Auth::user()->role,
            'role' => Role::where('name', Auth::user()->role)->first(),

        ], $this->dropdownService->getDropdownData()));
    }

    public function Allfinancials(Request $request)
    {
        $filters = $request->only([
            'search',
            'account_status',
            'project',
            'marital_status',
            'english_level',
            'residence',
            'black_list',
            'role',
        ]);

        $employees = $this->employeeService->filterEmployees($filters);

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $employees->load([
            'deductions' => function ($q) use ($currentMonth, $currentYear) {
                $q->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear);
            },
            'advanceDeductions' => function ($q) use ($currentMonth, $currentYear) {
                $q->whereMonth('deducted_at', $currentMonth)
                    ->whereYear('deducted_at', $currentYear);
            },

            'increases' => function ($q) use ($currentMonth, $currentYear) {
                $q->where('is_reward', '1')
                    ->whereMonth('effective_date', $currentMonth)
                    ->whereYear('effective_date', $currentYear);
            },
        ]);

        $employees->transform(function ($employee) {
            $currentMonthDeductions = $employee->deductions->sum('value');
            $advanceDeductions = $employee->advanceDeductions->sum('amount');

            $currentMonthIncreases = $employee->increases->sum('increase_amount');
            // dd($currentMonthIncreases);

            $grossSalary = $employee->salary + $currentMonthIncreases;
            $netSalary = $grossSalary - $currentMonthDeductions - $advanceDeductions;

            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'base_salary' => $employee->salary,
                'current_month_increases' => $currentMonthIncreases,
                'current_month_deductions' => $currentMonthDeductions,
                'advance_deductions' => $advanceDeductions,
                'net_salary' => $netSalary,
                'bank_details' => [
                    'owner_account_name' => $employee->owner_account_name,
                    'iban' => $employee->iban,
                    'bank_name' => $employee->bank_name,
                ],
                'account_status' => $employee->account_status,
            ];
        });

        $projectsObjects = Project::all();
        $supervisors = Employee::whereHas('user', function ($query) {
            $query->where('role', 'supervisor');
        })->select('id', 'name', 'project_id')->get();
        $area_managers = Employee::whereHas('user', function ($query) {
            $query->where('role', 'area_manager');
        })
            ->select('id', 'name', 'project_id')
            ->get();
        return view('Employees.financials', array_merge([
            'employees' => $employees,
            'projectsObjects' => $projectsObjects,
            'supervisors' => $supervisors,
            'area_managers' => $area_managers,
            'totalSalaries' => $employees->sum('base_salary'),
            'totalIncreases' => $employees->sum('current_month_increases'),
            'totalNetSalaries' => $employees->sum('net_salary'),
            'totalDeductions' => $employees->sum(function ($e) {
                return $e['current_month_deductions'] + $e['advance_deductions'];
            }),
            'employeesCount' => $employees->count(),
            'avgSalaries' => $employees->avg('base_salary'),
            'avgNetSalaries' => $employees->avg('net_salary'),
            'minSalaries' => $employees->min('base_salary'),
            'maxSalaries' => $employees->max('base_salary'),
            'currentMonth' => now()->format('F Y'),
        ], $this->dropdownService->getDropdownData()));
    }
    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        try {
            $employee = $this->employeeService->create($data);
            $admin = User::where('role', 'admin')->first();
            Notification::send($admin, new NewEmployeeNotification($employee));
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة الموظف بنجاح.',
                'data' => $employee
            ]);
        } catch (\Exception $e) {
            Log::error('Employee creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Employee $employee)
    {
        $approvedEditRequest = $employee->employeeRequests()
            ->where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereHas('requestType', function ($q) {
                $q->where('key', 'edit_employee_data');
            })
            ->latest()
            ->first();

        $editableField = $approvedEditRequest?->edited_field;
        $canEdit = $employee->hasActivePermission($employee, 'edit_employee_data', $editableField);
        $canReplace = $employee->hasActivePermission($employee, 'replace_employee');
        //   dd($canEdit);
        $employees = Employee::all();
        $supervisors = Employee::whereHas('user', function ($query) {
            $query->where('role', 'supervisor');
        })
            ->select('id', 'name', 'project_id')
            ->get();
        $area_managers = Employee::whereHas('user', function ($query) {
            $query->where('role', 'area_manager');
        })
            ->select('id', 'name', 'project_id')
            ->get();
        return view('Employees.show', array_merge([
            'emp' => $employee,
            'editedFields' => EmployeeEditRequest::editableFields(),
            'editableField' => $editableField,
            'canEdit' => $canEdit,
            'canReplace' => $canReplace,
            'employees' => $employees,
            'supervisors' => $supervisors,
            'area_managers' => $area_managers,
            'authRole' => Auth::user()->role,
            'role' => Role::where('name', Auth::user()->role)->first(),
            'tool_bag_count' => $employee->requestTypeCount('tool_bag'),
            'united_clothes_count' => $employee->requestTypeCount('united_clothes'),
            'generate_health_card_count' => $employee->requestTypeCount('generate_health_card'),

        ], $this->dropdownService->getDropdownData()));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();
        // dd('test');
        $image = $request->file('personal_image');


        try {
            $this->employeeService->updateEmployee($employee, $data, $image);

            return response()->json([
                'success' => true,
                'message' => 'تمت تعديل بيانات الموظف بنجاح.',
                'data' => $employee
            ]);
        } catch (\Exception $e) {
            Log::error('Employee edit failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function replace(ReplaceEmployeeRequest $request)
    {
        $data = $request->validated();

        try {
            $newEmployee = $this->employeeService->replaceEmployee($data);

            return response()->json([
                'success' => true,
                'message' => 'تم استبدال الموظف بنجاح وإضافة الموظف الجديد',
                'data' => [
                    'new_employee' => new EmployeeResource($newEmployee),
                    'redirect_url' => route('employees.show', $newEmployee->id)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Employee replacement failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء عملية الاستبدال: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function showReplacements($employee)
    {
        $oldEmployee = Employee::with(['replacements', 'replacements.newEmployee'])->first();
        return view('employees.replacements', compact('oldEmployee'));
    }
    public function showAlerts(Employee $employee)
    {
        $employee->load('alerts');
        return view('Employees.alerts', array_merge([
            'employee' => new EmployeeResource($employee)
        ], $this->dropdownService->getDropdownData()));
    }
    public function showDeductions(Employee $employee)
    {
        $employee->load('deductions');
        return view('Employees.deductions', array_merge([
            'employee' => new EmployeeResource($employee)
        ], $this->dropdownService->getDropdownData()));
    }
    public function showAdvances(Employee $employee)
    {
        $employee->load('advances');
        return view('Employees.advances', array_merge([
            'employee' => new EmployeeResource($employee)
        ], $this->dropdownService->getDropdownData()));
    }
    public function showAdvanceDeductions(Employee $employee)
    {
        $employee->load('advanceDeductions');
        return view('Employees.advance_deduction', array_merge([
            'employee' => new EmployeeResource($employee)
        ], $this->dropdownService->getDropdownData()));
    }
    public function showIncreases(Employee $employee, Request $request)
    {
        $year = $request->input('year', now()->year);
        $type = $request->input('type');
        $status = $request->input('status');
        $query = $employee->increases()
            ->whereYear('created_at', $year)
            ->latest();

        if ($type) {
            // Use is_reward boolean field instead of increase_type
            $query->where('is_reward', $type === 'reward');
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Calculate summary statistics
        $staticIncreasesQuery = clone $query;
        $rewardIncreasesQuery = clone $query;

        $staticIncreasesTotal = $staticIncreasesQuery->where('is_reward', false)->sum('increase_amount');
        $staticIncreasesCount = $staticIncreasesQuery->where('is_reward', false)->count();

        $rewardIncreasesTotal = $rewardIncreasesQuery->where('is_reward', true)->sum('increase_amount');
        $rewardIncreasesCount = $rewardIncreasesQuery->where('is_reward', true)->count();

        $totalIncreases = $staticIncreasesTotal + $rewardIncreasesTotal;
        $totalIncreasesCount = $staticIncreasesCount + $rewardIncreasesCount;
        return view('employees.increases', [
            'employee' => $employee,
            'increases' => $query,
            'staticIncreasesTotal' => $staticIncreasesTotal,
            'staticIncreasesCount' => $staticIncreasesCount,
            'rewardIncreasesTotal' => $rewardIncreasesTotal,
            'rewardIncreasesCount' => $rewardIncreasesCount,
            'totalIncreases' => $totalIncreases,
            'totalIncreasesCount' => $totalIncreasesCount,
        ]);
    }
    public function showTemporaryProjectAssignments(Employee $employee)
    {
        $assignments = TemporaryProjectAssignment::with(['fromProject', 'toProject', 'manager'])
            ->where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('Employees.temporary-projects', array_merge([
            'employee' => new EmployeeResource($employee),
            'assignments' => $assignments,
        ], $this->dropdownService->getDropdownData()));
    }
    public function allAlerts()
    {
        $query = Alert::with('employee')->latest();

        if (Auth::user()->role === 'project_manager') {
            $managedProjectIds = Project::where('manager_id', Auth::id())->pluck('id');
            $query->whereHas('employee', function ($q) use ($managedProjectIds) {
                $q->whereIn('project_id', $managedProjectIds);
            });
        }

        $alerts = $query->get();

        return view('Employees.ALL.alerts', array_merge([
            'alerts' => $alerts,
        ], $this->dropdownService->getDropdownData()));
    }

    public function allDeductions()
    {
        $query = Deduction::with('employee')->latest();

        if (Auth::user()->role === 'project_manager') {
            $managedProjectIds = Project::where('manager_id', Auth::id())->pluck('id');
            $query->whereHas('employee', function ($q) use ($managedProjectIds) {
                $q->whereIn('project_id', $managedProjectIds);
            });
        }

        $deductions = $query->get();

        return view('Employees.ALL.deductions', array_merge([
            'deductions' => $deductions,
        ], $this->dropdownService->getDropdownData()));
    }

    public function allAdvances()
    {
        $query = Advance::with('employee')->latest();

        if (Auth::user()->role === 'project_manager') {
            $managedProjectIds = Project::where('manager_id', Auth::id())->pluck('id');
            $query->whereHas('employee', function ($q) use ($managedProjectIds) {
                $q->whereIn('project_id', $managedProjectIds);
            });
        }

        $advances = $query->get();

        return view('Employees.ALL.advances', array_merge([
            'advances' => $advances,
        ], $this->dropdownService->getDropdownData()));
    }

    public function allAdvancesDeductions()
    {
        $query = AdvanceDeduction::with('employee')->latest();

        if (Auth::user()->role === 'project_manager') {
            $managedProjectIds = Project::where('manager_id', Auth::id())->pluck('id');
            $query->whereHas('employee', function ($q) use ($managedProjectIds) {
                $q->whereIn('project_id', $managedProjectIds);
            });
        }

        $advancesDeductions = $query->get();

        return view('Employees.ALL.advance_deduction', array_merge([
            'advancesDeductions' => $advancesDeductions,
        ], $this->dropdownService->getDropdownData()));
    }

    public function allIncreases(Request $request)
    {
        $year = $request->input('year', now()->year);
        $type = $request->input('type');
        $status = $request->input('status');
        $search = $request->input('search');

        $query = SalaryIncrease::with(['employee', 'manager'])
            ->whereYear('created_at', $year)
            ->latest();

        // Apply project manager filter if needed
        if (Auth::user()->role === 'project_manager') {
            $managedProjectIds = Project::where('manager_id', Auth::id())->pluck('id');
            $query->whereHas('employee', function ($q) use ($managedProjectIds) {
                $q->whereIn('project_id', $managedProjectIds);
            });
        }

        // Apply filters
        if ($type !== null) {
            $query->where('is_reward', $type);
        }

        if ($status) {
            $query->where('status', 'like', trim($status) . '%');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                    ->orWhere('increase_amount', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Calculate summary statistics
        $staticIncreasesQuery = clone $query;
        $rewardIncreasesQuery = clone $query;

        $staticIncreasesTotal = $staticIncreasesQuery->where('is_reward', false)->sum('increase_amount');
        $staticIncreasesCount = $staticIncreasesQuery->where('is_reward', false)->count();

        $rewardIncreasesTotal = $rewardIncreasesQuery->where('is_reward', true)->sum('increase_amount');
        $rewardIncreasesCount = $rewardIncreasesQuery->where('is_reward', true)->count();

        $totalIncreases = $staticIncreasesTotal + $rewardIncreasesTotal;
        $totalIncreasesCount = $staticIncreasesCount + $rewardIncreasesCount;

        $increases = $query->paginate(15);

        return view('Employees.ALL.increases', array_merge([
            'increases' => $increases,
            'staticIncreasesTotal' => $staticIncreasesTotal,
            'staticIncreasesCount' => $staticIncreasesCount,
            'rewardIncreasesTotal' => $rewardIncreasesTotal,
            'rewardIncreasesCount' => $rewardIncreasesCount,
            'totalIncreases' => $totalIncreases,
            'totalIncreasesCount' => $totalIncreasesCount,
            'currentYear' => $year,
        ], $this->dropdownService->getDropdownData()));
    }
    public function allTemporaryProjectAssignments()
    {
        $query = TemporaryProjectAssignment::with(['employee', 'fromProject', 'toProject', 'manager'])
            ->latest();

        if (Auth::user()->role === 'project_manager') {
            $managedProjectIds = Project::where('manager_id', Auth::id())->pluck('id');
            $query->where(function ($q) use ($managedProjectIds) {
                $q->whereHas('employee', function ($q) use ($managedProjectIds) {
                    $q->whereIn('project_id', $managedProjectIds);
                })->orWhereHas('toProject', function ($q) use ($managedProjectIds) {
                    $q->whereIn('id', $managedProjectIds);
                });
            });
        }

        $assignments = $query->get();

        return view('Employees.ALL.assignments', array_merge([
            'assignments' => $assignments,
        ], $this->dropdownService->getDropdownData()));
    }

    public function allReplacements()
    {
        $query = EmployeeReplacement::with(['oldEmployee', 'newEmployee'])->latest();

        if (Auth::user()->role === 'project_manager') {
            $managedProjectIds = Project::where('manager_id', Auth::id())->pluck('id');
            $query->where(function ($q) use ($managedProjectIds) {
                $q->whereHas('oldEmployee', function ($q) use ($managedProjectIds) {
                    $q->whereIn('project_id', $managedProjectIds);
                })->orWhereHas('newEmployee', function ($q) use ($managedProjectIds) {
                    $q->whereIn('project_id', $managedProjectIds);
                });
            });
        }

        $replacements = $query->get();

        return view('Employees.ALL.replacements', compact('replacements'));
    }

    public function temporaryAssignmentsView()
    {
        $user = Auth::user();

        $assignmentsQuery = TemporaryProjectAssignment::with(['employee.user', 'fromProject', 'toProject']);

        if ($user->role === 'project_manager') {
            $assignmentsQuery->whereHas('toProject', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }

        $assignments = $assignmentsQuery->get();

        return view('projects.temporary_assignments', compact('assignments'));
    }
    public function updatePhoto(Request $request, Employee $employee)
    {
        $request->validate([
            'profile_photo_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $employee->user;
//        dd($employee);

        if ($user->personal_image && Storage::exists('s3/' . $user->personal_image)) {
            Storage::delete('s3/' . $user->personal_image);
        }

        $path = $request->file('profile_photo_path')->store('employees/images', 'public');

        $user->personal_image = $path;
        $user->save();

        return redirect()->back()->with('success', 'تم تحديث الصورة الشخصية بنجاح.');
    }
   public function actions()
{
    $employees = Employee::withCount([
        'replacements',
        'alerts',
        'deductions',
        'advances',
        'increases',
        'temporaryAssignments',
        'employeeRequests',
        'advanceDeductions',
    ])->get();
//dd($employees);
    return view('Employees.actions', [
        'employees' => $employees,
    ]);
}

}
