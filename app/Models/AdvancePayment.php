<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvancePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'advance_id',
        'employee_id',
        'amount',
        'scheduled_date',
        'status',
        'payment_number',
        'original_scheduled_date',
        'postpone_reason',
        'created_from_payment_id',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'original_scheduled_date' => 'date',
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

    public function createdFromPayment()
    {
        return $this->belongsTo(AdvancePayment::class, 'created_from_payment_id');
    }

    public function postponedPayments()
    {
        return $this->hasMany(AdvancePayment::class, 'created_from_payment_id');
    }
}
