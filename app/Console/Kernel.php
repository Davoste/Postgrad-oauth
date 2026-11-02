<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Daily: alert students whose intent-to-submit date is within 14 days
        $schedule->command('thesis:check-deadlines')->daily();

        // 1st of every month: alert supervisors who missed a meeting last month
        $schedule->command('meetings:check-requirements')->monthlyOn(1, '08:00');

        // 1st of every month: alert supervisors whose quarterly/bi-annual report is overdue
        $schedule->command('reports:check-due')->monthlyOn(1, '08:30');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
