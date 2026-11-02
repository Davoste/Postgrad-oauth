<?php

namespace App\Services\Moodle;

use Illuminate\Support\Facades\Http;

/**
 * Thin wrapper over Moodle's REST Web Services API.
 *
 * All calls hit /webservice/rest/server.php with the token + wsfunction.
 * Laravel's Http::asForm() serialises nested arrays as users[0][field]=...,
 * which is exactly the shape Moodle expects — so no manual flattening is needed.
 */
class MoodleClient
{
    protected ?string $base;
    protected ?string $token;

    public function __construct(?string $base = null, ?string $token = null)
    {
        $this->base = $base ?? config('moodle.base_url');
        $this->base = $this->base ? rtrim($this->base, '/') : null;
        $this->token = $token ?? config('moodle.token');
    }

    /** Integration is only active when both a URL and token are configured. */
    public function enabled(): bool
    {
        return !empty($this->base) && !empty($this->token);
    }

    protected function call(string $function, array $params = [])
    {
        $response = Http::asForm()
            ->timeout(config('moodle.timeout', 20))
            ->post($this->base . '/webservice/rest/server.php', array_merge([
                'wstoken'            => $this->token,
                'wsfunction'         => $function,
                'moodlewsrestformat' => 'json',
            ], $params))
            ->throw();

        $data = $response->json();

        // Moodle returns HTTP 200 even for errors — they arrive as {exception, message, ...}
        if (is_array($data) && isset($data['exception'])) {
            throw new MoodleException($data['message'] ?? 'Moodle web service error', $function, $data);
        }

        return $data;
    }

    /** Site name + version — used to verify connectivity and the token. */
    public function siteInfo(): array
    {
        return $this->call('core_webservice_get_site_info');
    }

    /** Find a Moodle user by a field ('idnumber', 'email', 'username'); null if none. */
    public function findUserByField(string $field, string $value): ?array
    {
        $res = $this->call('core_user_get_users_by_field', ['field' => $field, 'values' => [$value]]);
        return $res[0] ?? null;
    }

    /** Create a user; returns ['id' => .., 'username' => ..]. */
    public function createUser(array $user): array
    {
        $res = $this->call('core_user_create_users', ['users' => [$user]]);
        return $res[0];
    }

    /** Add a user to a cohort (auto-enrols them into any courses linked to that cohort). */
    public function addToCohort(int $cohortId, int $userId): void
    {
        $this->call('core_cohort_add_cohort_members', [
            'members' => [[
                'cohorttype' => ['type' => 'id', 'value' => $cohortId],
                'usertype'   => ['type' => 'id', 'value' => $userId],
            ]],
        ]);
    }

    /** Manually enrol a user into a specific course. */
    public function enrolInCourse(int $courseId, int $userId, ?int $roleId = null): void
    {
        $this->call('enrol_manual_enrol_users', [
            'enrolments' => [[
                'roleid'   => $roleId ?? config('moodle.student_role_id', 5),
                'userid'   => $userId,
                'courseid' => $courseId,
            ]],
        ]);
    }
}
