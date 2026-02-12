<?php

namespace App\Models;

use App\Scopes\YearScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Namu\WireChat\Traits\Chatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Employee extends Model
{
    use Chatable;
    use HasFactory;

    protected $fillable = [
        'name',
        'joining_date',
        'work_duration',
        'job',
        'vehicle_info',
        'health_card',
        'user_id',
        'work_area',
        'project_id',
        'stop_reason',
        'salary',
        'salary_type',
        'english_level',
        'certificate_type',
        'marital_status',
        'members_number',
        'alerts_number',
        'deductions_number',
        'replaced_by_id',
        'owner_account_name',
        'supervisor_id',
        'area_manager_id',
        'manager_id',
        'iban',
        'bank_name',
        'last_working_date',
        'payload',
        'absence_days',
        'outstanding_advance_debt',
        'is_terminated',
        'termination_date',
        'termination_notes',
        'work_days',
    ];
    protected $casts = [
        'joining_date' => 'date',
        'vehicle_info' => 'array',
        'payload' => 'array',

    ];

    public function loginIps()
    {
        return $this->hasMany(EmployeeLoginIp::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id', 'user_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function areaManager()
    {
        return $this->belongsTo(Employee::class, 'area_manager_id');
    }


    function translateDurationToArabic($duration)
    {
        $replacements = [
            'years' => 'سنة',
            'year' => 'سنة',
            'months' => 'شهر',
            'month' => 'شهر',
            'days' => 'يوم',
            'day' => 'يوم',
            ',' => '،',
        ];

        return strtr($duration, $replacements);
    }

    public function getWorkDuration($joining_date)
    {
        $startDate = Carbon::parse($joining_date);

        $endDate = isset($this->payload['stop_date']) && $this->payload['stop_date']
            ? Carbon::parse($this->payload['stop_date'])
            : Carbon::now();

        $diff = $startDate->diff($endDate);

        return $this->translateDurationToArabic("{$diff->y} years, {$diff->m} months, {$diff->d} days");
    }
    public function currentWorkPeriod()
    {
        return $this->hasOne(EmployeeWorkHistory::class)
            ->whereNull('end_date')
            ->latest('start_date');
    }
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeByManager($query, $managerId)
    {
        return $query->whereHas('project', function ($q) use ($managerId) {
            $q->where('manager_id', $managerId);
        });
    }

    // app/Models/Employee.php

    public function getEnglishLevel(): string
    {
        return match ($this->english_level) {
            'basic' => 'مبتدئ',
            'intermediate' => 'متوسط',
            'advanced' => 'متقدم',
            default => 'غير محدد',
        };
    }

    public function getCertificateType(): string
    {
        return match ($this->certificate_type) {
            'high_school' => 'ثانوية عامة',
            'diploma' => 'دبلوم',
            'bachelor' => 'بكالوريوس',
            'master' => 'ماجستير',
            'phd' => 'دكتوراه',
            default => 'غير محدد',
        };
    }

    public function getMaritalStatus(): string
    {
        return match ($this->marital_status) {
            'single' => 'أعزب',
            'married' => 'متزوج',
            'divorced' => 'مطلق',
            'widowed' => 'أرمل',
            default => 'غير محدد',
        };
    }
    public function employeeRequests()
    {
        return $this->hasMany(EmployeeRequest::class, 'employee_id', 'id');
    }
    public function requestTypeCount(string $typeName): int
    {
        return $this->employeeRequests()
            ->whereHas('requestType', function ($query) use ($typeName) {
                $query->where('key', $typeName);
            })
            ->whereYear('created_at', 2025)
            ->count();
    }
    public function temporaryPermissions()
    {
        return $this->hasMany(TemporaryPermission::class, 'employee_id', 'id');
    }

    public function hasActivePermission($model, string $type, ?string $field = null, bool $returnObject = false)
    {
        $query = $this->temporaryPermissions()
            ->where('used', false)
            ->where('employee_id', $model->id);

        // Handle edit_employee_data case
        if ($type === 'edit_employee_data') {
            if (!$field) {
                return $returnObject ? null : false;
            }
            $query->where('edited_field', $field);
        }

        // Handle replace_employee case
        if ($type === 'replace_employee') {
            $query->whereNull('edited_field');
        }

        return $returnObject ? $query->first() : $query->exists();
    }



    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
    public function advances()
    {
        return $this->hasMany(Advance::class);
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }
    public function increases()
    {
        return $this->hasMany(SalaryIncrease::class);
    }

    public function replacements()
    {
        return $this->hasMany(EmployeeReplacement::class, 'old_employee_id');
    }
    public function replacedEmployee()
    {
        return $this->hasOne(Employee::class, 'replaced_by_id');
    }
    public function replacedBy()
    {
        return $this->hasOne(EmployeeReplacement::class, 'new_employee_id');
    }
    public function temporaryAssignments()
    {
        return $this->hasMany(TemporaryProjectAssignment::class);
    }
    public function getDisplayIdAttribute()
    {
        return $this->replacedBy?->id ?? $this->id;
    }
    public function oldEmployee()
    {
        return $this->belongsTo(Employee::class, 'old_employee_id');
    }


    public function getReplacementLabelAttribute()
    {
        if ($this->replacedBy) {
            return 'تم استبداله بـ: ' . $this->replacedBy->name;
        }

        if ($this->replacedEmployee) {
            return 'استبدل: ' . $this->replacedEmployee->name;
        }

        return 'لم يتم استبداله';
    }
    public function advanceDeductions()
    {
        return $this->hasMany(AdvanceDeduction::class);
    }

    public function advancePayments()
    {
        return $this->hasMany(AdvancePayment::class);
    }

}
