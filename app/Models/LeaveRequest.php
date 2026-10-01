<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'leave_type', 'start_date', 'end_date',
        'days_count', 'status', 'reviewed_by', 'notes',
        'response_notes', 'reviewed_at',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getLeaveTypeLabel(): string
    {
        return match ($this->leave_type) {
            'annual'    => 'سنوية',
            'sick'      => 'مرضية',
            'emergency' => 'طارئة',
            'maternity' => 'أمومة',
            'unpaid'    => 'بدون راتب',
            default     => 'غير محدد',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending'  => 'معلقة',
            'approved' => 'موافق',
            'rejected' => 'مرفوضة',
            default    => 'غير محدد',
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending'  => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default    => 'gray',
        };
    }
}
