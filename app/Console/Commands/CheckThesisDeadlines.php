<?php

namespace App\Console\Commands;

use App\Mail\CountdownAlert;
use App\Models\Thesis;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckThesisDeadlines extends Command
{
    protected $signature = 'thesis:check-deadlines';
    protected $description = 'Send intent-to-submit countdown alerts for theses due within 14 days';

    public function handle(): int
    {
        $urgent = Thesis::where('state', 'intent_to_submit')
            ->whereNotNull('intent_to_submit_date')
            ->where('intent_to_submit_date', '<=', now()->addDays(14)->toDateString())
            ->where('intent_to_submit_date', '>=', now()->toDateString())
            ->with('student.user')
            ->get();

        $sent = 0;
        foreach ($urgent as $thesis) {
            $daysLeft = (int) now()->diffInDays($thesis->intent_to_submit_date);
            try {
                Mail::to($thesis->student->user->email)
                    ->send(new CountdownAlert($thesis, $daysLeft));
                $sent++;
            } catch (\Exception $e) {
                $this->error("Failed to email {$thesis->student->user->email}: {$e->getMessage()}");
            }
        }

        $this->info("Deadline alerts sent: {$sent}");
        return Command::SUCCESS;
    }
}
