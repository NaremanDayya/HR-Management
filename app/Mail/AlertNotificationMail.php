<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageData;

    public function __construct(array $messageData)
    {
        $this->messageData = $messageData;
    }

    public function build()
    {
        return $this->subject('إنذار من الإدارة: ' . $this->messageData['alert_title'])
                    ->view('emails.alert_notification');
    }
}


