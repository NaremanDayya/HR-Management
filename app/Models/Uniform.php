<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Uniform extends Model
{
    protected $fillable = [
        'employee_id', 'tshirt_count', 'hat_count', 'id_card_count', 'tool_bag_count', 'received_at',
    ];

    protected $casts = [
        'received_at' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function isEligibleForFree(): bool
    {
        return $this->received_at->diffInDays(now()) >= 365;
    }

    public function daysUntilEligible(): int
    {
        $days = 365 - $this->received_at->diffInDays(now());
        return max(0, $days);
    }
}
