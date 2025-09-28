<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use App\Services\EmployeeViewDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    protected EmployeeViewDataService $dropdownService;

    public function __construct(EmployeeViewDataService $dropdownService)
    {
        $this->dropdownService = $dropdownService;
    }
    public function index()
    {
        $authUser = Auth::user();

        $query = Project::with('manager', 'employees')->latest();

        if ($authUser->role === 'project_manager') {
            $query->where('manager_id', $authUser->id);
        } elseif (in_array($authUser->role, ['admin', 'hr_manager', 'hr_assistant'])) {
        }

        $projects = $query->get();

        return view('Projects.table', compact('projects'));
    }
    public function store(ProjectRequest $request)
    {
        try {
            $validated = $request->validated();

            $project = Project::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء المشروع بنجاح',
                'data' => $project
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء المشروع',
                'error' => $e->getMessage(),
                'error_details' => config('app.debug') ? $e->getTrace() : null
            ], 500);
        }
    }


    public function update(ProjectRequest $request, Project $project)
    {
        try {
            $validated = $request->validated();

            // Update the project
            $project->update([
                'name' => $validated['name'],
                'manager_id' => $validated['manager_id'] ?? null,
                'description' => $validated['description'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المشروع بنجاح',
                'data' => [
                    'project' => $project,
                    'manager_name' => $project->manager ? $project->manager->name : null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في تحديث المشروع',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function projectStatistics(Request $request)
    {
        $authUser = Auth::user();
        $query = Project::query();

        if ($authUser->role === 'project_manager') {
            $query->where('manager_id', $authUser->id);
        }

        $totalProjects = $query->count();

        $projectsByManager = $query->clone()
            ->select('manager_id', DB::raw('count(*) as count'))
            ->groupBy('manager_id')
            ->with('manager:id,name')
            ->get();

        $projects = $query->clone()
            ->withCount([
                'activeEmployees as active_employees_count',
                'inactiveEmployees as inactive_employees_count',
                'incomingTransfers',
                'outgoingTransfers'
            ])->get();

        // Rest of your statistics calculations...
        $totalActiveEmployees = $projects->sum('active_employees_count');
        $totalInActiveEmployees = $projects->sum('inactive_employees_count');
        $maxActiveEmployeesProject = $projects->sortByDesc('active_employees_count')->first();
        $minActiveEmployeesProject = $projects->sortBy('active_employees_count')->first();

        // Get the project IDs for the filtered projects
        $projectIds = $query->pluck('id');

        // Update all statistics methods to filter by project IDs
        $ageGroups = $this->getEmployeeAgies($request, $projectIds);
        $nationalities = $this->getEmployeeNationalities($request, $projectIds);
        $salariesStats = $this->getEmployeeSalariesStats($request, $projectIds);
        $salaryCounts = $this->getEmployeeSalaryCounts($request, $projectIds);

        // Add employee roles statistics with project filtering
        $rolesStats = Employee::with('user')
            ->when($request->account_status === 'active', function ($q) {
                $q->where('account_status', 'active');
            })
            ->when($request->account_status === 'inactive', function ($q) {
                $q->where('account_status', 'inactive');
            })
            ->whereIn('project_id', $projectIds)
            ->select(DB::raw('users.role as role'), DB::raw('count(*) as count'))
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->groupBy('users.role')
            ->orderByDesc('count')
            ->pluck('count', 'role')
            ->toArray();

        return view('Projects.statistics', compact(
                'totalProjects',
                'projectsByManager',
                'projects',
                'maxActiveEmployeesProject',
                'minActiveEmployeesProject',
                'ageGroups',
                'nationalities',
                'salariesStats',
                'salaryCounts',
                'totalActiveEmployees',
                'totalInActiveEmployees',
                'rolesStats'
            ) + $this->dropdownService->getDropdownData());
    }
    public function getEmployeeAgies(Request $request, $projectIds = null)
    {
        $query = Employee::with('user');

        $status = $request->input('account_status');

        if (!empty($status)) {
            $query->whereHas('user', function ($q) use ($status) {
                $q->where('account_status', $status);
            });
        }

        // Filter by project IDs if provided
        if ($projectIds) {
            $query->whereIn('project_id', $projectIds);
        }

        $employees = $query->get();

        $ageGroups = $employees->groupBy(function ($employee) {
            $birthday = $employee->user->birthday;
            return $birthday ? \Carbon\Carbon::parse($birthday)->age : 'غير محدد';
        })->map(function ($group) {
            return count($group);
        })->sortKeys();

        return $ageGroups;
    }

    public function getEmployeeNationalities(Request $request, $projectIds = null)
    {
        $status = $request->get('account_status');

        $query = DB::table('employees')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->select('users.nationality', DB::raw('COUNT(*) as count'))
            ->groupBy('users.nationality');

        if (!empty($status)) {
            $query->where('users.account_status', $status);
        }

        // Filter by project IDs if provided
        if ($projectIds) {
            $query->whereIn('employees.project_id', $projectIds);
        }

        $results = $query->get();

        return $results->mapWithKeys(function ($item) {
            return [$item->nationality => $item->count];
        });
    }

    public function getEmployeeSalariesStats(Request $request, $projectIds = null)
    {
        $status = $request->get('account_status');

        $query = Employee::with('user')
            ->whereNotNull('project_id')
            ->whereNotNull('salary');

        if (!empty($status)) {
            $query->whereHas('user', function ($q) use ($status) {
                $q->where('account_status', $status);
            });
        }

        // Filter by project IDs if provided
        if ($projectIds) {
            $query->whereIn('project_id', $projectIds);
        }

        $employees = $query->get();

        logger('Employee salaries:', $employees->pluck('salary')->toArray());

        return [
            'salaryStats' => [
                'totalSalary' => $employees->sum('salary') ?? 0,
                'averageSalary' => $employees->avg('salary') ?? 0,
                'minSalary' => $employees->min('salary') ?? 0,
                'maxSalary' => $employees->max('salary') ?? 0,
                'employeeCount' => $employees->count()
            ]
        ];
    }

    public function getEmployeeSalaryCounts(Request $request, $projectIds = null)
    {
        $status = $request->get('account_status');

        $query = DB::table('employees')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->select('employees.salary', DB::raw('COUNT(*) as count'))
            ->groupBy('employees.salary')
            ->orderBy('employees.salary', 'asc');

        if (!empty($status)) {
            $query->where('users.account_status', $status);
        }

        // Filter by project IDs if provided
        if ($projectIds) {
            $query->whereIn('employees.project_id', $projectIds);
        }

        $results = $query->get();

        return $results->mapWithKeys(function ($item) {
            return [$item->salary => $item->count];
        });
    }

    // for every project
    public function showStatistics(Project $project, Request $request)
    {
        $status = $request->get('account_status');
        $projects = Project::withCount([
            'activeEmployees as active_employees_count',
            'inactiveEmployees as inactive_employees_count',
            'incomingTransfers',
            'outgoingTransfers'
        ])->get();
        $totalProjects = Project::count();

        $employeesByNationality = $this->getEmployeesByNationality($project, $status);
        $employeesByAgeGroup = $this->getEmployeesByAgeGroup($project, $status);
        $employeesByStatus = $this->getEmployeesByAccountStatus($project);
        $employeesSalaries = $this->getEmployeesSalaries($project, $status);

        // Add employee roles statistics
        $employeesByRole = $project->employees()
            ->when($status === 'active', function ($q) {
                $q->where('account_status', 'active');
            })
            ->when($status === 'inactive', function ($q) {
                $q->where('account_status', 'inactive');
            })
            ->with('user')
            ->get()
            ->groupBy(function ($employee) {
                return $employee->user ? $employee->user->role : 'unknown';
            })
            ->map->count()
            ->sortDesc()
            ->toArray();

        $totalSalary = $employeesSalaries['salaryStats']['totalSalary'];
        $averageSalary = $employeesSalaries['salaryStats']['averageSalary'];
        $minSalary = $employeesSalaries['salaryStats']['minSalary'];
        $maxSalary = $employeesSalaries['salaryStats']['maxSalary'];
        $salaryCounts = $employeesSalaries['salaryCounts'];

        $salaryStats = compact('totalSalary', 'averageSalary', 'minSalary', 'maxSalary');
        $project->loadCount(['activeEmployees', 'inactiveEmployees']);

        return view(
            'Projects.show',
            compact(
                'project',
                'employeesByNationality',
                'employeesByAgeGroup',
                'employeesByStatus',
                'employeesSalaries',
                'salaryStats',
                'salaryCounts',
                'projects',
                'totalProjects',
                'employeesByRole' // Add this
            ) + $this->dropdownService->getDropdownData()
        );
    }
    public function getEmployeesByNationality(Project $project, $status = null)
    {
        $query = $project->employees()->with('user');

        if (!empty($status)) {
            $query->whereHas('user', function ($q) use ($status) {
                $q->where('account_status', $status);
            });
        }

        $employees = $query->get();

        // Group employees by nationality
        return $employees->groupBy(function ($employee) {
            return $employee->user->nationality ?? 'غير محدد';
        });
    }

    public function getEmployeesByAgeGroup(Project $project, $status = null)
    {
        $query = $project->employees()->with('user');

        if (!empty($status)) {
            $query->whereHas('user', function ($q) use ($status) {
                $q->where('account_status', $status);
            });
        }

        $employees = $query->get();

        return $employees->groupBy(function ($employee) {
            $birthday = $employee->user->birthday;
            return $birthday ? \Carbon\Carbon::parse($birthday)->age : 'غير محدد';
        })->sortKeys();
    }


    public function getEmployeesByAccountStatus(Project $project)
    {
        $employees = $project->employees()->with('user')->get();

        return $employees->groupBy(function ($employee) {
            return $employee->user->account_status ?? 'غير محدد';
        });
    }

    public function getEmployeesSalaries(Project $project, $status = null)
    {
        $query = $project->employees()->with('user');

        if (!empty($status)) {
            $query->whereHas('user', function ($q) use ($status) {
                $q->where('account_status', $status);
            });
        }

        $employees = $query->get();

        // تجميع الموظفين حسب الراتب، وحساب عدد الموظفين لكل راتب، مع ترتيب المفاتيح (الرواتب) تصاعدياً
        $salaryCounts = $employees->groupBy('salary')->map->count()->sortKeys();

        $totalSalary = $employees->sum('salary');
        $averageSalary = $employees->avg('salary');
        $minSalary = $employees->min('salary');
        $maxSalary = $employees->max('salary');

        return [
            'salaryCounts' => $salaryCounts,
            'salaryStats' => [
                'totalSalary' => $totalSalary,
                'averageSalary' => $averageSalary,
                'minSalary' => $minSalary,
                'maxSalary' => $maxSalary,
            ],
        ];
    }
}
