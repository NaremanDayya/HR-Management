<?php

namespace App\Notifications;

use App\Models\ProjectDeleteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectDeleteRequestSubmittedNotification extends Notification implements ShouldBroadcastNow
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
        $project = $this->projectDeleteRequest->project;
        $requester = $this->projectDeleteRequest->requester;

        return (new MailMessage)
            ->subject('طلب حذف مشروع بانتظار المراجعة')
            ->line("قام \"{$requester->name}\" بطلب حذف المشروع \"{$project->name}\".")
            ->action('مراجعة الطلب', route('project-delete-requests.index'))
            ->line('يرجى مراجعة الطلب وقبوله أو رفضه.');
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
        $project = $this->projectDeleteRequest->project;
        $requester = $this->projectDeleteRequest->requester;

        return [
            'project_id' => $project->id,
            'project_name' => $project->name,
            'icon' => '🗑️',
            'title' => 'طلب حذف مشروع',
            'message' => "🗑️ \"{$requester->name}\" طلب حذف المشروع \"{$project->name}\" وبانتظار مراجعتك.",
            'url' => route('project-delete-requests.index'),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
