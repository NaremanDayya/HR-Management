<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewEmployeeDeductionNotification extends Notification implements ShouldQueue, ShouldBroadcastNow
{
    use Queueable;

    public function __construct(
        public Employee $employee,
        public float $amount,
        public string $reason
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
            'message'     => "💸 تم تطبيق خصم بقيمة {$this->amount}₪ على الموظف {$this->employee->name} بسبب: {$this->reason}",
 'url'         => route('employees.deductions',[
                'employee' =>$this->employee->id,
            ]),        ];
    }

    public function broadcastOn(): array
    {
        return ['employee-deductions'];
    }
}
