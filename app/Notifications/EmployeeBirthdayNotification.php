<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Deliberately NOT ShouldQueue: this fires from a scheduled job, and queued
// notifications never get persisted unless a queue worker is running. The
// existing BirthdayGreeting notification follows the same synchronous pattern.
class EmployeeBirthdayNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public User $employeeUser;

    public function __construct(User $employeeUser)
    {
        $this->employeeUser = $employeeUser;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎂 عيد ميلاد موظف')
            ->line("اليوم هو عيد ميلاد الموظف \"{$this->employeeUser->name}\".")
            ->line('لا تنسَ تهنئته بهذه المناسبة!');
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
            'employee_id' => $this->employeeUser->id,
            'employee_name' => $this->employeeUser->name,
            'icon' => '🎂',
            'title' => 'عيد ميلاد موظف',
            'message' => "🎂 اليوم هو عيد ميلاد الموظف \"{$this->employeeUser->name}\"، لا تنسَ تهنئته!",
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
