<?php

namespace App\Mail;

use App\Models\Thesis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CountdownAlert extends Mailable
{
    use Queueable, SerializesModels;

    public Thesis $thesis;
    public int $daysLeft;

    public function __construct(Thesis $thesis, int $daysLeft)
    {
        $this->thesis   = $thesis;
        $this->daysLeft = $daysLeft;
    }

    public function build(): self
    {
        return $this
            ->subject("Thesis Submission Deadline: {$this->daysLeft} Day(s) Remaining")
            ->view('emails.countdown-alert');
    }
}
