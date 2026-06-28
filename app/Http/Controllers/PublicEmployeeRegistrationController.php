<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicEmployeeRegistrationRequest;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use App\Notifications\EmployeeSelfSubmissionNotification;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PublicEmployeeRegistrationController extends Controller
{
    public const ALLOWED_ROLES = [
        'shelf_stacker' => 'مصفف أرفف',
        'supervisor' => 'مشرف',
        'area_manager' => 'مشرف المشرفين',
    ];

    public function show(Project $project, string $role)
    {
        abort_unless(array_key_exists($role, self::ALLOWED_ROLES), 404);

        $supervisors = collect();
        $areaManagers = collect();

        if ($role === 'shelf_stacker') {
            $supervisors = Employee::where('project_id', $project->id)
                ->whereHas('user', function ($q) {
                    $q->where('role', 'supervisor')->where('account_status', 'active');
                })
                ->pluck('name', 'id');
        }

        if ($role === 'supervisor') {
            $areaManagers = Employee::where('project_id', $project->id)
                ->whereHas('user', function ($q) {
                    $q->where('role', 'area_manager')->where('account_status', 'active');
                })
                ->pluck('name', 'id');
        }

        return view('Employees.self-registration', [
            'project' => $project,
            'role' => $role,
            'roleLabel' => self::ALLOWED_ROLES[$role],
            'supervisors' => $supervisors,
            'areaManagers' => $areaManagers,
            'maritalStatuses' => [
                'single' => 'أعزب',
                'married' => 'متزوج',
                'divorced' => 'مطلق',
                'widowed' => 'أرمل',
            ],
            'englishLevels' => [
                'basic' => 'مبتدئ',
                'intermediate' => 'متوسط',
                'advanced' => 'متقدم',
            ],
            'certificateTypes' => [
                'high_school' => 'ثانوية عامة',
                'diploma' => 'دبلوم',
                'bachelor' => 'بكالوريوس',
                'master' => 'ماجستير',
                'phd' => 'دكتوراه',
            ],
            'shirtSizes' => ['xxs' => 'XXS', 'xs' => 'XS', 's' => 'S', 'm' => 'M', 'l' => 'L', 'xl' => 'XL', 'xxl' => 'XXL', '3xl' => '3XL', '4xl' => '4XL', '5xl' => '5XL'],
            'pantsSizes' => ['28' => '28', '30' => '30', '32' => '32', '34' => '34', '36' => '36', '38' => '38', '40' => '40', '42' => '42', '44' => '44', '46' => '46', '48' => '48'],
            'shoesSizes' => array_combine(range(36, 50), range(36, 50)),
        ]);
    }

    public function store(Project $project, string $role, PublicEmployeeRegistrationRequest $request)
    {
        abort_unless(array_key_exists($role, self::ALLOWED_ROLES), 404);

        $data = $request->validated();
        $data['role'] = $role;
        $data['project'] = $project->id;
        $data['personal_image'] = $request->file('personal_image');

        if ($role !== 'shelf_stacker') {
            unset($data['supervisor']);
        }
        if ($role !== 'supervisor') {
            unset($data['area_manager']);
        }

        $employee = DB::transaction(function () use ($data) {
            return app(EmployeeService::class)->create($data, 'pending');
        });

        $recipients = User::whereIn('role', ['admin', 'hr_manager', 'hr_assistant'])->get();
        if ($project->manager) {
            $recipients->push($project->manager);
        }

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients->unique('id'), new EmployeeSelfSubmissionNotification($employee));
        }

        return view('Employees.self-registration-success', ['project' => $project]);
    }
}
