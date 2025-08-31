<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeLoginIp extends Model
{
    use HasFactory;

    protected $table = 'employee_login_ips';

    protected $fillable = [
        'employee_id',
        'ip_address',
        'is_allowed',
        'is_temporary',
        'allowed_until',
        'blocked_at',
    ];

    protected $casts = [
        'is_allowed' => 'boolean',
        'is_temporary' => 'boolean',
        'allowed_until' => 'datetime',
        'blocked_at' => 'datetime',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNull('blocked_at');
    }

    public function scopePermanent($query)
    {
        return $query->where('is_temporary', false);
    }

    public function scopeTemporary($query)
    {
        return $query->where('is_temporary', true);
    }

    public function scopeValidTemporary($query)
    {
        return $query->temporary()
                     ->where('allowed_until', '>=', now())
                     ->whereNull('blocked_at');
    }

    public function isBlocked()
    {
        return !is_null($this->blocked_at);
    }

    public function isExpiredTemporary()
    {
        return $this->is_temporary && $this->allowed_until && now()->greaterThan($this->allowed_until);
    }
}
