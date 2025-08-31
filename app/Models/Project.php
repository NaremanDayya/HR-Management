<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Scopes\YearScope;

class Project extends Model
{
    protected $fillable = [
        'name',
        'manager_id',
        'description',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new YearScope());
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function employees()
    {
        return $this->hasMany(employee::class, 'project_id');
    }
    public function activeEmployees()
    {
        return $this->hasMany(Employee::class, 'project_id')
            ->whereHas('user', function ($query) {
                $query->where('account_status', 'active');
            });
    }

    public function inactiveEmployees()
    {
        return $this->hasMany(Employee::class, 'project_id')
            ->whereHas('user', function ($query) {
                $query->where('account_status', '!=', 'active');
            });
    }
    public function getActiveEmployeesCountAttribute()
    {
        return $this->activeEmployees()->count();
    }

    public function getInactiveEmployeesCountAttribute()
    {
        return $this->inactiveEmployees()->count();
    }
    public function incomingTransfers()
    {
        return $this->hasMany(TemporaryProjectAssignment::class, 'to_project_id');
    }

    public function outgoingTransfers()
    {
        return $this->hasMany(TemporaryProjectAssignment::class, 'from_project_id');
    }
}
