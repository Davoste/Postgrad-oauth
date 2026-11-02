<?php

namespace App\Mail;

use App\Models\Thesis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProgressReportDue extends Mailable
{
    use Queueable, SerializesModels;

    public Thesis $thesis;
    public string $reportType;

    public function __construct(Thesis $thesis, string $reportType)
    {
        $this->thesis     = $thesis;
        $this->reportType = $reportType;
    }

    public function build(): self
    {
        $typeLabel = $this->reportType === 'quarterly' ? 'Quarterly' : 'Bi-Annual';
        return $this
            ->subject("{$typeLabel} Progress Report Due — {$this->thesis->student->user->name}")
            ->view('emails.progress-report-due');
    }
}
