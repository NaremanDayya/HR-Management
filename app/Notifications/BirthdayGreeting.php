<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BirthdayGreeting extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => '🎉 تهانينا بعيد ميلادك!',
            'message' => "كل عام وأنت بخير يا {$notifiable->name}! 🎂 نتمنى لك عامًا مليئًا بالنجاح والسعادة!",
            'icon' => '🎂',
        ];
    }
    public function broadcastOn(): array
    {
        return ['birthday'];
    }
}
