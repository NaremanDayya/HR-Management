<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeNotification extends Notification implements ShouldQueue, ShouldBroadcastNow
{
    use Queueable;

    public $employee;

    /**
     * Create a new notification instance.
     */
    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast']; // Add 'mail' if you want email notifications
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('موظف جديد')
            ->line('تم إضافة موظف جديد إلى النظام')
            ->action('عرض الموظف', route('employees.show', $this->employee->id))
            ->line('شكراً لاستخدامك نظامنا!');
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->name,
            'message' => 'تم إضافة موظف جديد (' . $this->employee->name . ')',
            'url' => route('employees.show', $this->employee->id),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->name,
            'message' => 'تم إضافة موظف جديد (' . $this->employee->name . ')',
            'url' => route('employees.show', $this->employee->id),
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->name,
            'message' => "🆕 تم إضافة موظف جديد: \"{$this->employee->name}\" إلى النظام.",
            'url' => route('employees.show', [
                'employee' => $this->employee->id,

            ]),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {

        return [
            new PrivateChannel("new-employee"),
        ];
    }
}
