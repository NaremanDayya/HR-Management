<?php

namespace App\Notifications;

use App\Models\BankUpdateRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Deliberately NOT ShouldQueue, same reasoning as EmployeeSelfSubmissionNotification:
// this must land the moment the employee submits the bank-change request.
class BankUpdateRequestSubmittedNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public BankUpdateRequest $bankUpdateRequest;

    public function __construct(BankUpdateRequest $bankUpdateRequest)
    {
        $this->bankUpdateRequest = $bankUpdateRequest;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employee = $this->bankUpdateRequest->employee;

        return (new MailMessage)
            ->subject('طلب تعديل بيانات بنكية بانتظار المراجعة')
            ->line("قام الموظف \"{$employee->name}\" بإرسال طلب لتعديل بياناته البنكية.")
            ->action('مراجعة الطلب', route('bank-update-requests.index', ['employee_id' => $employee->id]))
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
        $employee = $this->bankUpdateRequest->employee;

        return [
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'icon' => '🏦',
            'title' => 'طلب تعديل بيانات بنكية',
            'message' => "🏦 الموظف \"{$employee->name}\" أرسل طلبًا لتعديل بياناته البنكية وبانتظار مراجعتك.",
            'url' => route('bank-update-requests.index', ['employee_id' => $employee->id]),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
