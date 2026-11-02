<?php

namespace App\Mail;

use App\Models\SupervisionMeeting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingLogged extends Mailable
{
    use Queueable, SerializesModels;

    public SupervisionMeeting $meeting;

    public function __construct(SupervisionMeeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function build(): self
    {
        return $this
            ->subject('Meeting Logged — Your Signature Required')
            ->view('emails.meeting-logged');
    }
}
