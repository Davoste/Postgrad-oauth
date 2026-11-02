<?php

namespace App\Mail;

use App\Models\Thesis;
use App\Models\ThesisSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public Thesis $thesis;
    public ThesisSubmission $submission;
    public string $recipientRole;

    public function __construct(Thesis $thesis, ThesisSubmission $submission, string $recipientRole = 'supervisor')
    {
        $this->thesis        = $thesis;
        $this->submission    = $submission;
        $this->recipientRole = $recipientRole;
    }

    public function build(): self
    {
        $stageName = Thesis::STAGE_LABELS[$this->submission->stage] ?? $this->submission->stage;

        return $this
            ->subject("New Submission: {$stageName} — {$this->thesis->title}")
            ->view('emails.submission-received');
    }
}
