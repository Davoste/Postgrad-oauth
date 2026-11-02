<?php

namespace App\Jobs;

use App\Models\Student;
use App\Services\Moodle\MoodleClient;
use App\Services\Moodle\MoodleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Create (or link) a Moodle account for a newly enrolled student, idempotently.
 *
 * Safe to dispatch always: if the Moodle integration isn't configured, or the
 * student is already linked, the job simply returns.
 */
class ProvisionMoodleUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public int $studentId)
    {
    }

    public function handle(MoodleClient $moodle): void
    {
        if (!$moodle->enabled()) {
            return; // integration switched off
        }

        $student = Student::with('user', 'application', 'programme')->find($this->studentId);
        if (!$student || !$student->user || $student->moodle_user_id) {
            return; // gone, or already provisioned (idempotent)
        }

        $user     = $student->user;
        $appl     = $student->application;
        $idnumber = $student->reg_no;

        // Reuse an existing Moodle account if one already matches (idnumber, then email)
        $existing = $moodle->findUserByField('idnumber', $idnumber)
            ?? $moodle->findUserByField('email', $user->email);

        if ($existing) {
            $moodleId = (int) $existing['id'];
        } else {
            $first = $appl->first_name ?? Str::before($user->name, ' ');
            $last  = $appl->surname ?? (Str::contains($user->name, ' ') ? Str::afterLast($user->name, ' ') : $first);

            $payload = [
                'username'  => $this->username($idnumber, $user->email),
                'idnumber'  => $idnumber,
                'firstname' => $first ?: 'Student',
                'lastname'  => $last ?: $idnumber,
                'email'     => $user->email,
                'auth'      => config('moodle.auth', 'manual'),
                'lang'      => 'en',
            ];
            // Only send a password for password-based (manual) auth
            if (config('moodle.auth', 'manual') === 'manual') {
                $payload['password'] = $this->tempPassword();
                $payload['preferences'] = [['type' => 'auth_forcepasswordchange', 'value' => 1]];
            }

            try {
                $created  = $moodle->createUser($payload);
                $moodleId = (int) $created['id'];
            } catch (MoodleException $e) {
                Log::warning('Moodle provisioning failed for student ' . $student->id . ': ' . $e->getMessage());
                throw $e; // let the queue retry
            }
        }

        $student->forceFill([
            'moodle_user_id'   => $moodleId,
            'moodle_synced_at' => now(),
        ])->save();

        // Optionally drop the student into a default cohort (which can auto-enrol courses)
        if ($cohort = config('moodle.default_cohort_id')) {
            try {
                $moodle->addToCohort((int) $cohort, $moodleId);
            } catch (MoodleException $e) {
                Log::warning('Moodle cohort add failed for student ' . $student->id . ': ' . $e->getMessage());
            }
        }
    }

    /** Build a valid, unique-ish Moodle username from the admission number. */
    protected function username(string $regNo, string $email): string
    {
        $slug = strtolower(preg_replace('/[^A-Za-z0-9]+/', '.', trim($regNo, '/')));
        return $slug !== '' ? trim($slug, '.') : strtolower($email);
    }

    protected function tempPassword(): string
    {
        // Meets a typical Moodle policy: length, upper, lower, digit, symbol
        return Str::random(10) . 'A9#';
    }
}
