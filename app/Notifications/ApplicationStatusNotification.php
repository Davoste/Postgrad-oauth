<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class ApplicationStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application,
        public string $state,
        public array $extra = [],
        
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $app = $this->application;
        $mail = (new MailMessage)->subject($this->subjectLine());

        switch ($this->state) {
            case 'submitted':
                $mail->greeting("Dear {$app->first_name},")
                    ->line("We have received your application ({$app->ref_no}) for {$app->programme->name}.")
                    ->line('You can track your application status any time using the link below.')
                    ->action('Track application', url("/track/{$app->tracking_token}"));
                break;

            case 'hod_review_required':
                $mail->greeting('Postgraduate application for review')
                    ->line("Application {$app->ref_no} ({$app->full_name}) for {$app->programme->name} awaits your recommendation.")
                    ->action('Review application', url('/login'));
                break;

            case 'director_review_required':
                $mail->greeting('Application recommended for admission')
                    ->line("The HOD has recommended {$app->full_name} ({$app->ref_no}) for {$app->programme->name}.")
                    ->action('Review for decision', url('/login'));
                break;

            case 'offer_made':
                $mail->greeting("Dear {$app->first_name},")
                    ->line("Congratulations! You have been offered admission to {$app->programme->name}.")
                    ->line('Please accept or decline your offer online using the link below.')
                    ->action('Respond to offer', url("/track/{$app->tracking_token}"));
                break;

            case 'enrolled':
                $mail->greeting("Welcome to JOOUST, {$app->first_name}!")
                    ->line("Congratulations — you have been admitted. Admission number: {$this->extra['adm_no']}")
                    ->line("This is to confirm your admission to {$app->programme->name} at JOOUST.");

                if (!empty($this->extra['password'])) {
                    $mail->line('Your student account has been created.')
                        ->line("Temporary password: {$this->extra['password']}")
                        ->line('Please log in and change your password immediately.');
                } else {
                    $mail->line('Your existing portal account has been upgraded — the "Thesis Progress", "Submissions", "Feedback", "Milestones" and "Meetings" tabs are now unlocked on your dashboard.')
                        ->line('Log in with your usual portal password to get started.');
                }

                $mail->line('Kindly find attached your admission letter.')
                    ->line('Please keep this email for your records.')
                    ->action('Log in to the portal', url('/login'));

                $mail->attachData($this->extra['pdf_content'], $this->extra['pdf_name'] ?? 'admission-letter.pdf', [
                    'mime' => 'application/pdf',
                ]);

                break;

            case 'rejected':
                $mail->greeting("Dear {$app->first_name},")
                    ->line("We regret to inform you that your application ({$app->ref_no}) for {$app->programme->name} was not successful on this occasion.");
                if (!empty($this->extra['reason'])) {
                    $mail->line('**Feedback / reason:**')
                         ->line($this->extra['reason']);
                }
                $mail->line('You are welcome to apply again in a future intake. If you have questions, contact the Board of Postgraduate Studies at bps@jooust.ac.ke.');
                break;
        }

        return $mail->salutation('Regards, Board of Postgraduate Studies (BPS)');
    }

    private function subjectLine(): string
    {
        return match ($this->state) {
            'submitted'                => 'Application received — JOOUST Postgraduate',
            'hod_review_required',
            'director_review_required' => 'Postgraduate application awaiting your review',
            'offer_made'               => 'Offer of admission — JOOUST Postgraduate',
            'enrolled'                 => 'Your JOOUST student account is ready',
            'rejected'                 => 'JOOUST Postgraduate application outcome',
            default                    => 'JOOUST Postgraduate application update',
        };
    }
}
