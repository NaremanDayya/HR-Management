<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BlockedIpLoginAttempt extends Notification
{
    use Queueable;

    public $employee;
    public $ip;

    public function __construct($employee, $ip)
    {
        $this->employee = $employee;
        $this->ip = $ip;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'محاولة دخول من جهاز غير مصرح',
            'message' => "قام الموظف {$this->employee->name} بمحاولة تسجيل دخول من IP غير مصرح: {$this->ip}",
            'employee_id' => $this->employee->id,
            'ip_address' => $this->ip,
            'time' => now()->format('Y-m-d H:i:s'),
        ];
    }
    public function broadcastOn(): array
    {
        return ['employee-login-ip'];
    }
}
