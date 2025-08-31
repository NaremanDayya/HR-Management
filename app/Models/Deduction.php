<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'value',
        'reason',
        'employee_id',
        'manager_id',
        'payload'
    ];
    protected $casts = [
        'payload' => 'array',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new YearScope);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
