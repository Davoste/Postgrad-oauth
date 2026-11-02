<?php

namespace App\Console\Commands;

use App\Mail\ProgressReportDue;
use App\Models\ProgressReport;
use App\Models\Thesis;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckProgressReportsDue extends Command
{
    protected $signature = 'reports:check-due';
    protected $description = 'Remind supervisors when quarterly or bi-annual progress reports are due';

    public function handle(): int
    {
        $sent = 0;

        $activeTheses = Thesis::whereNotIn('state', ['concept_development', 'graduated'])
            ->with(['student.user', 'supervisor.user', 'progressReports'])
            ->whereNotNull('supervisor_id')
            ->get();

        foreach ($activeTheses as $thesis) {
            $dueType = $this->getReportDueType($thesis);
            if (!$dueType) continue;

            try {
                Mail::to($thesis->supervisor->user->email)
                    ->send(new ProgressReportDue($thesis, $dueType));
                $sent++;
            } catch (\Exception $e) {
                $this->error("Email failed: {$e->getMessage()}");
            }
        }

        $this->info("Progress report reminders sent: {$sent}");
        return Command::SUCCESS;
    }

    /**
     * Determine if a quarterly or bi-annual report is now due.
     * Returns 'quarterly', 'biannual', or null.
     */
    private function getReportDueType(Thesis $thesis): ?string
    {
        $lastReport = $thesis->progressReports()->latest('submitted_at')->first();
        $since      = $lastReport ? $lastReport->submitted_at : $thesis->created_at;
        $monthsSince = (int) $since->diffInMonths(now());

        // Bi-annual: every 6 months
        if ($monthsSince >= 6) return 'biannual';

        // Quarterly: every 3 months
        if ($monthsSince >= 3) return 'quarterly';

        return null;
    }
}
