<?php

namespace App\Notifications;

use App\Models\EmployeeRequest;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class EmployeeRequestStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public EmployeeRequest $editRequest,
        public string $typeKey,
        public string $status
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
        $statusText = $this->status === 'approved' ? 'تمت الموافقة على' : 'تم رفض';
        $statusIcon = $this->status === 'approved' ? '✅' : '❌'; 

        $typeMessages = [
            'edit_employee_data'     => 'طلب تعديل بيانات موظف',
            'generate_health_card'   => 'طلب إصدار كرت صحي',
            'replace_employee'       => 'طلب استبدال موظف',
            'salary_advance'         => 'طلب سلفة',
            'salary_increase'        => 'طلب زيادة راتب',
            'stop_employee'          => 'طلب إيقاف موظف',
            'temporary_assignment'   => 'طلب تكليف مؤقت',
            'tool_bag'               => 'طلب حقيبة أدوات',
            'united_clothes'         => 'طلب زي موحد',
        ];

        $messageType = $typeMessages[$this->typeKey] ?? 'طلب موظف';

        return [
            'request_id'   => $this->editRequest->id,
            'request_type' => $this->typeKey,
            'message'      => "{$statusIcon} {$statusText} {$messageType} المقدم من قبل {$this->editRequest->manager->name}.",
            'status_icon'  => $statusIcon, // You can use this in the frontend if needed separately
            'url'          => route('employee-request.index'),
        ];
    }
    public function broadcastOn(): array
    {
        return ['employee-request-status'];
    }
}
