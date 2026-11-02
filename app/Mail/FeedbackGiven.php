<?php

namespace App\Mail;

use App\Models\Thesis;
use App\Models\ThesisSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackGiven extends Mailable
{
    use Queueable, SerializesModels;

    public Thesis $thesis;
    public ThesisSubmission $submission;
    public string $decision;
    public string $comments;
    public string $reviewerName;

    public function __construct(Thesis $thesis, ThesisSubmission $submission, string $decision, string $comments, string $reviewerName = '')
    {
        $this->thesis       = $thesis;
        $this->submission   = $submission;
        $this->decision     = $decision;
        $this->comments     = $comments;
        $this->reviewerName = $reviewerName;
    }

    public function build(): self
    {
        $stageName   = Thesis::STAGE_LABELS[$this->submission->stage] ?? $this->submission->stage;
        $decisionStr = ucfirst(str_replace('_', ' ', $this->decision));

        return $this
            ->subject("Feedback on Your {$stageName} Submission — {$decisionStr}")
            ->view('emails.feedback-given');
    }
}
