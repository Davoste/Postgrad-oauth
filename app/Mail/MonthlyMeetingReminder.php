<?php

namespace App\Mail;

use App\Models\Supervisor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MonthlyMeetingReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Supervisor $supervisor;
    public array $studentsWithNoMeeting;

    public function __construct(Supervisor $supervisor, array $studentsWithNoMeeting)
    {
        $this->supervisor           = $supervisor;
        $this->studentsWithNoMeeting = $studentsWithNoMeeting;
    }

    public function build(): self
    {
        return $this
            ->subject('Action Required: Monthly Supervision Meeting Not Recorded')
            ->view('emails.monthly-meeting-reminder');
    }
}
