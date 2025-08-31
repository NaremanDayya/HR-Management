<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;

class SalaryIncrease extends Model
{
    protected $fillable = [
        'employee_id',
        'manager_id',
        'request_id',
        'previous_salary',
        'increase_amount',
        'increase_percentage',
        'new_salary',
        'reason',
        'status',
        'approved_at',
        'effective_date',
        'is_applied',
        'increase_type',
        'reward_month',
        'is_reward',
    ];
    protected $casts = [
        'is_applied' => 'boolean',
        'is_reward' => 'boolean',
        'effective_date' => 'date',
        'approved_at' => 'date',
        'increase_type' => 'string',

    ];

    protected static function booted()
    {
        static::addGlobalScope(new YearScope);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function request()
    {
        return $this->belongsTo(EmployeeRequest::class, 'request_id');
    }
}
