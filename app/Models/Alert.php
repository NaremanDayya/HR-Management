<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'reason',
        'employee_id',
        'title',
        'is_read',
        'manager_id',
        'image_url',
        'message_sent',
    ];
    protected $casts = [
        'is_read' => 'boolean',
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
