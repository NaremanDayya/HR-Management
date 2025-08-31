<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReplacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'old_employee_id',
        'new_employee_id',
        'last_working_date',
        'replacement_date',
        'reason'
    ];

    protected $casts =
    [
        'last_working_date' => 'date',
        'replacement_date' => 'date',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new YearScope);
    }

    public function oldEmployee()
    {
        return $this->belongsTo(Employee::class, 'old_employee_id');
    }

    public function newEmployee()
    {
        return $this->belongsTo(Employee::class, 'new_employee_id');
    }
}
