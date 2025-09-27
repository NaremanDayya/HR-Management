<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EmployeeWorkHistory extends Model
{
    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'status',
        'stop_reason',
        'work_days'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $appends = ['duration_text', 'period_text', 'is_active'];

    // Auto-calculate work days when saving
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculateWorkDays();
        });
    }

    public function calculateWorkDays()
    {
        $start = Carbon::parse($this->start_date);
        $end = $this->end_date ? Carbon::parse($this->end_date) : now();

        $this->attributes['work_days'] = $start->diffInDays($end);
    }

    /**
     * Get duration in readable format
     */
    public function getDurationTextAttribute()
    {
        $start = Carbon::parse($this->start_date);
        $end = $this->end_date ? Carbon::parse($this->end_date) : now();

        $years = (int) $start->diffInYears($end);
        $months = (int) $start->diffInMonths($end) % 12;
        $days = (int) $start->diffInDays($end) % 30;

        $parts = [];

        if ($years > 0) {
            $parts[] = $years . ' سنة';
        }

        if ($months > 0) {
            $parts[] = $months . ' شهر';
        }

        if ($days > 0 || ($years === 0 && $months === 0)) {
            $parts[] = $days . ' يوم';
        }

        return implode(' و ', $parts);
    }

    /**
     * Get period text
     */
    public function getPeriodTextAttribute()
    {
        $start = Carbon::parse($this->start_date)->format('Y-m-d');
        $end = $this->end_date ? Carbon::parse($this->end_date)->format('Y-m-d') : 'حتى الآن';

        return "من {$start} إلى {$end}";
    }

    /**
     * Check if period is active
     */
    public function getIsActiveAttribute()
    {
        return is_null($this->end_date);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
