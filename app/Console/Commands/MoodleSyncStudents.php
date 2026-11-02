<?php

namespace App\Console\Commands;

use App\Jobs\ProvisionMoodleUser;
use App\Models\Student;
use App\Services\Moodle\MoodleClient;
use Illuminate\Console\Command;

class MoodleSyncStudents extends Command
{
    protected $signature = 'moodle:sync-students {--all : Re-queue every student, not just unsynced ones}';
    protected $description = 'Provision existing students into Moodle (backfill)';

    public function handle(MoodleClient $moodle): int
    {
        if (!$moodle->enabled()) {
            $this->error('Moodle is not configured. Set MOODLE_BASE_URL and MOODLE_TOKEN in .env.');
            return self::FAILURE;
        }

        $query = Student::query();
        if (!$this->option('all')) {
            $query->whereNull('moodle_user_id');
        }

        $count = 0;
        $query->each(function (Student $student) use (&$count) {
            ProvisionMoodleUser::dispatch($student->id);
            $count++;
        });

        $this->info("Queued {$count} student(s) for Moodle provisioning.");
        $this->line('Ensure a queue worker is running: php artisan queue:work');
        return self::SUCCESS;
    }
}
