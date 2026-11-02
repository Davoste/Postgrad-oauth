<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use App\Models\OAuth\OAuthAccessToken;
use App\Models\OAuth\OAuthAuthCode;
use App\Models\OAuth\OAuthClient;
use App\Models\OAuth\OAuthRefreshToken;
use Illuminate\Http\Request;

/**
 * Token endpoint — exchanges an authorization code (or refresh token) for an
 * access token. Called server-to-server by Moodle, not by the browser, so
 * this must stay CSRF-exempt (see VerifyCsrfToken::$except).
 */
class TokenController extends Controller
{
    public function issue(Request $request)
    {
        return match ($request->input('grant_type')) {
            'authorization_code' => $this->fromAuthCode($request),
            'refresh_token'      => $this->fromRefreshToken($request),
            default              => response()->json(['error' => 'unsupported_grant_type'], 400),
        };
    }

    private function client(Request $request): ?OAuthClient
    {
        // Clients may authenticate via HTTP Basic auth or POST body — both
        // are standard OAuth2, Moodle's issuer supports either.
        [$clientId, $clientSecret] = [
            $request->getUser() ?? $request->input('client_id'),
            $request->getPassword() ?? $request->input('client_secret'),
        ];

        if (!$clientId || !$clientSecret) {
            return null;
        }

        $client = OAuthClient::where('client_id', $clientId)->where('revoked', false)->first();

        return ($client && $client->checkSecret($clientSecret)) ? $client : null;
    }

    private function fromAuthCode(Request $request)
    {
        $request->validate(['code' => 'required|string', 'redirect_uri' => 'required|string']);

        $client = $this->client($request);
        if (!$client) {
            return response()->json(['error' => 'invalid_client'], 401);
        }

        $authCode = OAuthAuthCode::where('code', $request->code)
            ->where('oauth_client_id', $client->id)
            ->first();

        if (!$authCode || !$authCode->isValid() || $authCode->redirect_uri !== $request->redirect_uri) {
            return response()->json(['error' => 'invalid_grant'], 400);
        }

        // Codes are single-use.
        $authCode->update(['revoked' => true]);

        [$accessToken, $plainAccess] = OAuthAccessToken::issue($authCode->user_id, $client->id, $authCode->scopes);
        [, $plainRefresh] = OAuthRefreshToken::issue($accessToken->id);

        return $this->tokenResponse($plainAccess, $plainRefresh, $accessToken);
    }

    private function fromRefreshToken(Request $request)
    {
        $request->validate(['refresh_token' => 'required|string']);

        $client = $this->client($request);
        if (!$client) {
            return response()->json(['error' => 'invalid_client'], 401);
        }

        $refresh = OAuthRefreshToken::findValidByPlainToken($request->refresh_token);
        if (!$refresh || $refresh->accessToken->oauth_client_id !== $client->id) {
            return response()->json(['error' => 'invalid_grant'], 400);
        }

        $old = $refresh->accessToken;
        $refresh->update(['revoked' => true]);
        $old->update(['revoked' => true]);

        [$accessToken, $plainAccess] = OAuthAccessToken::issue($old->user_id, $client->id, $old->scopes);
        [, $plainRefresh] = OAuthRefreshToken::issue($accessToken->id);

        return $this->tokenResponse($plainAccess, $plainRefresh, $accessToken);
    }

    private function tokenResponse(string $accessToken, string $refreshToken, OAuthAccessToken $model)
    {
        return response()->json([
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => now()->diffInSeconds($model->expires_at),
            'scope'         => $model->scopes,
        ]);
    }
}
