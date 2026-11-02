<?php

return [
    // Base URL of the Moodle site, e.g. https://elearning.jooust.ac.ke
    'base_url' => env('MOODLE_BASE_URL'),

    // Web-services token for a dedicated integration user (see MOODLE-INTEGRATION.md)
    'token' => env('MOODLE_TOKEN'),

    // Auth method used for provisioned accounts: 'manual' (Moodle password) or an
    // external method like 'oauth2' / 'ldap' when you wire up single sign-on.
    'auth' => env('MOODLE_AUTH', 'manual'),

    // Optional: add every provisioned postgraduate to this cohort id.
    'default_cohort_id' => env('MOODLE_DEFAULT_COHORT_ID'),

    // Moodle role id for a student is 5 by default.
    'student_role_id' => (int) env('MOODLE_STUDENT_ROLE_ID', 5),

    // HTTP timeout (seconds)
    'timeout' => (int) env('MOODLE_TIMEOUT', 20),
];
