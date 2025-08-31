<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewEmployeeAlertNotification extends Notification implements ShouldQueue, ShouldBroadcastNow
{
    use Queueable;

    public function __construct(
        public Employee $employee,
        public string $title
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->buildPayload();
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->buildPayload());
    }

    private function buildPayload(): array
    {
        return [
            'employee_id' => $this->employee->id,
            'message'     => "🚨 تم إصدار إنذار جديد للموظف {$this->employee->name} بعنوان: {$this->title}",
            'url'         => route('employees.alerts',[
                'employee' =>$this->employee->id,
            ]),
        ];
    }

    public function broadcastOn(): array
    {
        return ['employee-alerts'];
    }
}
