<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirm Your Email — ' . brand('short_name') . ' Postgraduate Portal')
            ->view('emails.verify-email', [
                'url' => $this->verificationUrl($notifiable),
                'user' => $notifiable,
            ]);
    }
}