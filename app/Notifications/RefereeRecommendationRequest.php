<?php

namespace App\Notifications;

use App\Models\ApplicationReferee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefereeRecommendationRequest extends Notification
{
    use Queueable;

    public function __construct(public ApplicationReferee $referee) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $application = $this->referee->application;

        return (new MailMessage)
            ->subject('Academic reference requested — JOOUST Postgraduate')
            ->greeting("Dear {$this->referee->name},")
            ->line("{$application->full_name} has listed you as an academic referee for {$application->programme->name}.")
            ->line('Please submit your confidential recommendation using the secure link below.')
            ->action('Write recommendation', route('referee.show', $this->referee->token))
            ->line('The link is personal to you. Please do not forward it.');
    }
}