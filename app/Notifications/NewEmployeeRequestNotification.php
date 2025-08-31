<?php

namespace App\Notifications;

use App\Models\EmployeeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Broadcasting\PrivateChannel;

class NewEmployeeRequestNotification extends Notification implements ShouldQueue, ShouldBroadcastNow
{
    use Queueable;

    public function __construct(
        public EmployeeRequest $editRequest,
        public string $typeKey
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast']; // Add 'mail' if needed
    }

    public function toMail(object $notifiable): MailMessage
    {
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

        return (new MailMessage)
            ->subject($messageType)
            ->line("تم تقديم {$messageType} من قبل {$this->editRequest->manager->name}.")
            ->action('عرض الطلب', route('employee-request.index'))
            ->line('يرجى مراجعة الطلب المقدم.');
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
            'message'      => "✏️ {$messageType} من قبل {$this->editRequest->manager->name}.",
            'url'          => route('employee-request.index'),
        ];
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("employee-requests"),
        ];
    }
}
