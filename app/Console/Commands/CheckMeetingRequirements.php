<?php

namespace App\Console\Commands;

use App\Mail\MonthlyMeetingReminder;
use App\Models\Supervisor;
use App\Models\SupervisionMeeting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckMeetingRequirements extends Command
{
    protected $signature = 'meetings:check-requirements';
    protected $description = 'Alert supervisors who had no meeting with any active student last month';

    public function handle(): int
    {
        $lastMonth = now()->subMonth();
        $year  = $lastMonth->year;
        $month = $lastMonth->month;

        $supervisors = Supervisor::with(['user', 'theses.student.user'])
            ->whereHas('theses', fn($q) => $q->whereNotIn('state', ['graduated']))
            ->get();

        $sent = 0;

        foreach ($supervisors as $supervisor) {
            $studentsWithNoMeeting = [];

            foreach ($supervisor->theses as $thesis) {
                if (in_array((string)$thesis->state, ['graduated'])) continue;

                $hasMeeting = SupervisionMeeting::where('supervisor_id', $supervisor->id)
                    ->where('student_id', $thesis->student_id)
                    ->whereYear('meeting_date', $year)
                    ->whereMonth('meeting_date', $month)
                    ->exists();

                if (!$hasMeeting) {
                    $studentsWithNoMeeting[] = $thesis->student;
                }
            }

            if (!empty($studentsWithNoMeeting)) {
                try {
                    Mail::to($supervisor->user->email)
                        ->send(new MonthlyMeetingReminder($supervisor, $studentsWithNoMeeting));
                    $sent++;
                } catch (\Exception $e) {
                    $this->error("Email failed for {$supervisor->user->email}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Meeting reminders sent: {$sent}");
        return Command::SUCCESS;
    }
}
