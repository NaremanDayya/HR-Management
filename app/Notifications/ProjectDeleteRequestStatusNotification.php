<?php

namespace App\Notifications;

use App\Models\ProjectDeleteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectDeleteRequestStatusNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public ProjectDeleteRequest $projectDeleteRequest;

    public function __construct(ProjectDeleteRequest $projectDeleteRequest)
    {
        $this->projectDeleteRequest = $projectDeleteRequest;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $approved = $this->projectDeleteRequest->status === 'approved';
        $project = $this->projectDeleteRequest->project;

        $mail = (new MailMessage)
            ->subject($approved ? "تمت الموافقة على طلب حذف المشروع \"{$project->name}\"" : "تم رفض طلب حذف المشروع \"{$project->name}\"")
            ->line($approved
                ? "تمت الموافقة على طلبك وتم إيقاف المشروع \"{$project->name}\" وأرشفته."
                : "نأسف، تم رفض طلبك لحذف المشروع \"{$project->name}\".");

        if (! $approved && $this->projectDeleteRequest->rejection_reason) {
            $mail->line('سبب الرفض: ' . $this->projectDeleteRequest->rejection_reason);
        }

        return $mail;
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
        $approved = $this->projectDeleteRequest->status === 'approved';
        $project = $this->projectDeleteRequest->project;

        return [
            'project_id' => $project->id,
            'icon' => $approved ? '✅' : '❌',
            'title' => $approved ? 'تمت الموافقة على طلب حذف المشروع' : 'تم رفض طلب حذف المشروع',
            'message' => $approved
                ? "✅ تمت الموافقة على طلبك وتم إيقاف المشروع \"{$project->name}\" وأرشفته."
                : "❌ تم رفض طلبك لحذف المشروع \"{$project->name}\"" . ($this->projectDeleteRequest->rejection_reason ? ': ' . $this->projectDeleteRequest->rejection_reason : '.'),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
