<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class TemporaryPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'employee_id',
        'request_id',
        'edited_field',
        'used',
    ];

    protected $casts = [
        'used' => 'boolean',
    ];
protected static function booted()
{
    static::addGlobalScope(new YearScope);
}
    // 🔗 Sales rep relation
    public function manager()
    {
        return $this->belongsTo(Manager::class ,'manager_id');
    }

    public function employeeRequest()
    {
        return $this->belongsTo(EmployeeRequest::class ,'request_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }

    public function isActive()
    {
        return !$this->used ;
    }
}

