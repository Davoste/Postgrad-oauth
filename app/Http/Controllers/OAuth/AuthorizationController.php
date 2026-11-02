<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use App\Models\OAuth\OAuthAuthCode;
use App\Models\OAuth\OAuthClient;
use Illuminate\Http\Request;

/**
 * OAuth2 Authorization Code grant — the "portal as identity provider" half.
 *
 * Moodle's OAuth2 auth plugin (or any relying party) sends the browser here
 * with ?response_type=code&client_id=...&redirect_uri=...&state=...
 * If the student isn't logged into the portal, Laravel's normal 'auth'
 * middleware bounces them to /login first — they log in ONCE, here, and
 * that's the single sign-on.
 */
class AuthorizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $validated = $this->validateRequest($request);
        if ($validated instanceof \Illuminate\Http\RedirectResponse) {
            return $validated;
        }
        [$client, $redirectUri, $scopes, $state] = $validated;

        // First-party / trusted clients (your own Moodle instance) skip the
        // consent screen entirely — asking "let the portal's own LMS see
        // your name and email?" is friction with no real decision in it.
        if ($client->trusted) {
            return $this->issueCodeAndRedirect($client, $redirectUri, $scopes, $state);
        }

        return view('oauth.authorize', [
            'client'      => $client,
            'redirectUri' => $redirectUri,
            'scopes'      => $scopes,
            'state'       => $state,
        ]);
    }

    public function approve(Request $request)
    {
        $request->validate([
            'client_id'    => 'required|string',
            'redirect_uri' => 'required|string',
        ]);

        $client = OAuthClient::where('client_id', $request->client_id)->where('revoked', false)->firstOrFail();

        if (!$client->redirectUriAllowed($request->redirect_uri)) {
            abort(400, 'redirect_uri does not match a registered URI for this client.');
        }

        return $this->issueCodeAndRedirect($client, $request->redirect_uri, $request->scope, $request->state);
    }

    public function deny(Request $request)
    {
        $params = http_build_query(array_filter([
            'error' => 'access_denied',
            'state' => $request->state,
        ]));

        return redirect($request->redirect_uri . (str_contains($request->redirect_uri, '?') ? '&' : '?') . $params);
    }

    private function issueCodeAndRedirect(OAuthClient $client, string $redirectUri, ?string $scopes, ?string $state)
    {
        $code = OAuthAuthCode::issue(auth()->id(), $client->id, $redirectUri, $scopes);

        $params = http_build_query(array_filter([
            'code'  => $code->code,
            'state' => $state,
        ]));

        return redirect($redirectUri . (str_contains($redirectUri, '?') ? '&' : '?') . $params);
    }

    /**
     * @return array{0: OAuthClient, 1: string, 2: ?string, 3: ?string}|\Illuminate\Http\RedirectResponse
     */
    private function validateRequest(Request $request)
    {
        $request->validate([
            'response_type' => 'required|in:code',
            'client_id'     => 'required|string',
            'redirect_uri'  => 'required|string',
        ]);

        $client = OAuthClient::where('client_id', $request->client_id)->where('revoked', false)->first();

        if (!$client) {
            abort(400, 'Unknown client_id.');
        }

        if (!$client->redirectUriAllowed($request->redirect_uri)) {
            abort(400, 'redirect_uri does not match a registered URI for this client.');
        }

        return [$client, $request->redirect_uri, $request->scope, $request->state];
    }
}
