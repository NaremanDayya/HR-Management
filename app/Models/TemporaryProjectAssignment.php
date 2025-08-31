<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryProjectAssignment extends Model
{
      use HasFactory;

    protected $fillable = [
        'employee_id',
        'from_project_id',
        'to_project_id',
        'manager_id',
        'reason',
        'start_date',
        'end_date',
        'status',
        'approved_at',
        'request_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'date',
    ];
protected static function booted()
{
    static::addGlobalScope(new YearScope);
}
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function fromProject()
    {
        return $this->belongsTo(Project::class, 'from_project_id');
    }
     public function toProject()
    {
        return $this->belongsTo(Project::class, 'to_project_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function request()
    {
        return $this->belongsTo(EmployeeRequest::class, 'request_id');
    }

    // Accessors / Helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
