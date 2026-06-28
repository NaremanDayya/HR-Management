<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Deliberately NOT ShouldQueue: queued notifications never get persisted unless
// a queue worker is running, and this needs to land reliably the moment an
// employee submits their self-registration form.
class EmployeeSelfSubmissionNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public Employee $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('طلب تسجيل موظف جديد بانتظار المراجعة')
            ->line("قام الموظف \"{$this->employee->name}\" بتعبئة نموذج التسجيل الذاتي لمشروع \"{$this->employee->project?->name}\".")
            ->action('مراجعة الطلب', route('employees.show', $this->employee->id))
            ->line('يرجى مراجعة بياناته وقبولها أو رفضها.');
    }

    public function toArray(object $notifiable): array
    {
        return $this->payload();
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->payload();
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->payload());
    }

    private function payload(): array
    {
        return [
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->name,
            'project_name' => $this->employee->project?->name,
            'icon' => '📝',
            'title' => 'طلب تسجيل موظف جديد',
            'message' => "📝 الموظف \"{$this->employee->name}\" قام بتعبئة نموذج تسجيل ذاتي لمشروع \"{$this->employee->project?->name}\" وبانتظار مراجعتك.",
            'url' => route('employees.show', $this->employee->id),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
