<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;

class AdvanceDeduction extends Model
{
    protected $fillable = [
        'advance_id',
        'employee_id',
        'amount',
        'deducted_at',
    ];

    protected $casts = [
        'deducted_at' => 'date',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new YearScope);
    }
    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
