<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Server-to-server OAuth2 token exchange (Moodle → portal); the
        // client_secret is the authentication here, not a session cookie.
        'oauth/token',
    ];
}
