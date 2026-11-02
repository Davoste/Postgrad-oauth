<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmissionLetterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Application $application,
        public string $admNo,
        public string $password,
        public string $pdfContent,
        public string $pdfName = 'admission-letter.pdf'
    ) {}

    public function build()
    {
        return $this->subject('Your JOOUST student account is ready')
            ->view('emails.admission-letter')
            ->with([
                'application' => $this->application,
                'admNo' => $this->admNo,
                'password' => $this->password,
            ])
            ->attachData($this->pdfContent, $this->pdfName, [
                'mime' => 'application/pdf',
            ]);
    }
}
