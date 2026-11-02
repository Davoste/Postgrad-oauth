<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Your Password — JOOUST Postgraduate Portal')
            ->view('emails.reset-password', [
                'url'  => $url,
                'user' => $notifiable,
            ]);
    }
}
