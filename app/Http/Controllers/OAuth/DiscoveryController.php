<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;

/**
 * OpenID Connect Discovery document (RFC-8414 / OIDC Discovery 1.0).
 *
 * Moodle's OAuth2 "standard" issuer type only exposes a single "Service base
 * URL" field (no separate Authorization/Token/UserInfo URL inputs) — it
 * fetches {service_base_url}/.well-known/openid-configuration and reads the
 * three endpoint URLs from here automatically. This is the piece that makes
 * that work: set "Service base URL" to this app's root URL in Moodle and the
 * rest is discovered.
 */
class DiscoveryController extends Controller
{
    public function show()
    {
        $base = url('/');

        return response()->json([
            'issuer'                                => $base,
            'authorization_endpoint'                 => route('oauth.authorize'),
            'token_endpoint'                          => route('oauth.token'),
            'userinfo_endpoint'                       => route('oauth.userinfo'),
            'jwks_uri'                                 => null,
            'scopes_supported'                        => ['openid', 'profile', 'email'],
            'response_types_supported'                => ['code'],
            'grant_types_supported'                    => ['authorization_code', 'refresh_token'],
            'subject_types_supported'                  => ['public'],
            'token_endpoint_auth_methods_supported'    => ['client_secret_post', 'client_secret_basic'],
            'claims_supported'                         => ['sub', 'id', 'email', 'email_verified', 'name', 'given_name', 'family_name', 'idnumber'],
        ]);
    }
}
