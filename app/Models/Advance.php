<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'manager_id',
        'amount',
        'percentage',
        'salary',
        'status',
        'requested_at',
        'approved_at',
        'request_id',
        'notes',
        'monthly_deduction',
        'months_to_repay',
        'months_remaining',
        'start_deduction_at',
        'is_fully_paid',
    ];

    protected $casts = [
        'requested_at' => 'date',
        'approved_at' => 'date',
        'start_deduction_at' => 'date',
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
    public function deductions()
    {
        return $this->hasMany(AdvanceDeduction::class);
    }

    public function payments()
    {
        return $this->hasMany(AdvancePayment::class);
    }
}
