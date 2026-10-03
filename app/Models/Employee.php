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
        'overtime_hours',
        'overtime_days',
        'outstanding_advance_debt',
        'is_terminated',
        'termination_date',
        'termination_notes',
        'work_days',
        'is_blacklisted',
        'is_sponsorship_employee',
        'passport_number',
        'sponsorship_documents',
        'passport_issue_date',
        'id_expiry_date',
        'driver_license_number',
        'medical_insurance',
        'wives_count',
        'languages',
        'residential_address',
    ];
    protected $casts = [
        'joining_date'       => 'date',
        'passport_issue_date'=> 'date',
        'id_expiry_date'     => 'date',
        'vehicle_info'       => 'array',
        'payload'            => 'array',
        'languages'              => 'array',
        'residential_address'    => 'array',
        'sponsorship_documents'  => 'array',
        'is_blacklisted'         => 'boolean',
        'is_sponsorship_employee'=> 'boolean',
    ];

    public const BLACKLIST_STOP_REASONS = ['سوء اداء', 'سوء أداء'];

    public static function matchesBlacklist(?string $name, ?string $idCard = null, ?string $phone = null, ?string $email = null): bool
    {
        return static::where(function ($query) use ($name, $idCard, $phone, $email) {
            $query->where('name', $name);

            if ($idCard) {
                $query->orWhereHas('user', fn ($q) => $q->where('id_card', $idCard));
            }

            if ($phone) {
                $query->orWhereHas('user', fn ($q) => $q->where('contact_info->phone_number', $phone));
            }

            if ($email) {
                $query->orWhereHas('user', fn ($q) => $q->where('email', $email));
            }
        })
            ->whereIn('stop_reason', self::BLACKLIST_STOP_REASONS)
            ->exists();
    }

    public function loginIps()
    {
        return $this->hasMany(EmployeeLoginIp::class);
    }


    public function uniform()
    {
        return $this->hasOne(Uniform::class);
    }

    public function uniformRequests()
    {
        return $this->hasMany(UniformRequest::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /** Total leave days accrued from joining_date to today based on Saudi labor law. */
    public function getTotalAccruedLeaveDays(): float
    {
        if (!$this->joining_date) return 0;

        $joining = $this->joining_date;
        $today   = \Carbon\Carbon::now();
        $fiveYearMark = $joining->copy()->addYears(5);

        if ($today <= $fiveYearMark) {
            // All service under 5 years: 21 days/year
            return $joining->diffInDays($today) * (21 / 365);
        }

        // Service spans the 5-year mark
        $daysUnder5 = $joining->diffInDays($fiveYearMark);
        $daysOver5  = $fiveYearMark->diffInDays($today);

        return ($daysUnder5 * (21 / 365)) + ($daysOver5 * (30 / 365));
    }

    /** Total approved leave days taken. */
    public function getTotalLeaveDaysTaken(): int
    {
        return $this->leaveRequests()
            ->where('status', 'approved')
            ->sum('days_count');
    }

    /** Remaining leave balance (accrued - taken), floored to 0. */
    public function getLeaveDaysRemaining(): float
    {
        return max(0, floor($this->getTotalAccruedLeaveDays()) - $this->getTotalLeaveDaysTaken());
    }

    /** Annual leave entitlement for the current service year (21 or 30 days). */
    public function getAnnualLeaveEntitlement(): int
    {
        if (!$this->joining_date) return 21;
        return $this->joining_date->diffInYears(\Carbon\Carbon::now()) >= 5 ? 30 : 21;
    }

    /** Flight ticket entitlement: 50% salary (<5 years), 100% salary (>=5 years). */
    public function getFlightTicketEntitlement(): array
    {
        if (!$this->joining_date || !$this->salary) {
            return ['label' => 'غير محدد', 'amount' => 0, 'type' => 'none'];
        }
        $years = $this->joining_date->diffInYears(\Carbon\Carbon::now());
        if ($years < 5) {
            return ['label' => 'نصف الراتب', 'amount' => $this->salary * 0.5, 'type' => 'half'];
        }
        return ['label' => 'راتب كامل', 'amount' => $this->salary, 'type' => 'full'];
    }

    /** Days until id_expiry_date (negative = already expired). */
    public function getIdExpiryDays(): ?int
    {
        if (!$this->id_expiry_date) return null;
        return (int) \Carbon\Carbon::now()->diffInDays($this->id_expiry_date, false);
    }

    /** Days until passport expires (passport does not have separate expiry — use id_expiry_date for iqama/visa). */
    public function getPassportExpiry(): ?string
    {
        // Passport expiry is not tracked separately; this returns passport_issue_date + 5 years as a rough estimate
        // but we rely on id_expiry_date for the actual residency/visa expiry.
        return null;
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

    public function bankUpdateRequests()
    {
        return $this->hasMany(BankUpdateRequest::class);
    }

    public function calculateOvertimePay(int $monthDays): float
    {
        $dailyRate = $monthDays > 0 ? $this->salary / $monthDays : 0;
        $hourlyRate = $dailyRate / 8;

        $overtimeHoursPay = ($this->overtime_hours ?? 0) * $hourlyRate * 1.5;
        $overtimeDaysPay = ($this->overtime_days ?? 0) * $dailyRate * 1.5;

        return $overtimeHoursPay + $overtimeDaysPay;
    }

}
