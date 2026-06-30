<?php

namespace App\Notifications;

use App\Models\BankUpdateRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BankUpdateRequestStatusNotification extends Notification implements ShouldBroadcastNow
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
        $approved = $this->bankUpdateRequest->status === 'approved';

        $mail = (new MailMessage)
            ->subject($approved ? 'تمت الموافقة على طلب تعديل بياناتك البنكية' : 'تم رفض طلب تعديل بياناتك البنكية')
            ->line($approved
                ? 'تمت الموافقة على طلبك وتحديث بياناتك البنكية في النظام.'
                : 'نأسف، تم رفض طلبك لتعديل البيانات البنكية.');

        if (! $approved && $this->bankUpdateRequest->rejection_reason) {
            $mail->line('سبب الرفض: ' . $this->bankUpdateRequest->rejection_reason);
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
        $approved = $this->bankUpdateRequest->status === 'approved';

        return [
            'employee_id' => $this->bankUpdateRequest->employee_id,
            'icon' => $approved ? '✅' : '❌',
            'title' => $approved ? 'تمت الموافقة على طلب البيانات البنكية' : 'تم رفض طلب البيانات البنكية',
            'message' => $approved
                ? '✅ تمت الموافقة على طلبك لتعديل البيانات البنكية وتم التحديث في النظام.'
                : '❌ تم رفض طلبك لتعديل البيانات البنكية' . ($this->bankUpdateRequest->rejection_reason ? ': ' . $this->bankUpdateRequest->rejection_reason : '.'),
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
