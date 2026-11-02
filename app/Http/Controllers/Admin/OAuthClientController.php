<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OAuth\OAuthClient;
use Illuminate\Http\Request;

class OAuthClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $clients = OAuthClient::latest()->get();

        return view('admin.oauth-clients', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:150'],
            'redirect_uri' => ['required', 'string'],
            'trusted'      => ['sometimes', 'boolean'],
        ]);

        [$client, $secret] = OAuthClient::generate(
            $request->name,
            $request->redirect_uri,
            $request->boolean('trusted')
        );
        $client->update(['user_id' => $request->user()->id]);

        return redirect()->route('admin.oauth-clients.index')
            ->with('newClient', ['client_id' => $client->client_id, 'client_secret' => $secret, 'name' => $client->name])
            ->with('success', "Client \"{$client->name}\" created. Copy the secret now — it won't be shown again.");
    }

    public function toggleTrusted(OAuthClient $oauthClient)
    {
        $oauthClient->update(['trusted' => !$oauthClient->trusted]);

        return back()->with('success', $oauthClient->trusted
            ? "\"{$oauthClient->name}\" will now skip the consent screen for users."
            : "\"{$oauthClient->name}\" will now show the consent screen again.");
    }

    public function destroy(OAuthClient $oauthClient)
    {
        $oauthClient->update(['revoked' => true]);

        return back()->with('success', "\"{$oauthClient->name}\" revoked. Existing tokens for it will stop working.");
    }
}
