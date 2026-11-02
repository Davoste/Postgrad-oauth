<?php

namespace App\Mail;

use App\Models\Supervisor;
use App\Models\Thesis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupervisorAllocated extends Mailable
{
    use Queueable, SerializesModels;

    public Thesis $thesis;
    public Supervisor $supervisor;
    public string $recipientRole;

    public function __construct(Thesis $thesis, Supervisor $supervisor, string $recipientRole)
    {
        $this->thesis         = $thesis;
        $this->supervisor     = $supervisor;
        $this->recipientRole  = $recipientRole;
    }

    public function build(): self
    {
        $subject = $this->recipientRole === 'student'
            ? "Supervisor Assigned: {$this->supervisor->user->name}"
            : "New Student Assigned: {$this->thesis->student->user->name}";

        return $this->subject($subject)->view('emails.supervisor-allocated');
    }
}
