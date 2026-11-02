<?php

namespace App\Models\OAuth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OAuthClient extends Model
{
    protected $table = 'oauth_clients';

    protected $fillable = ['user_id', 'name', 'client_id', 'client_secret', 'redirect_uri', 'trusted', 'revoked'];

    protected $casts = ['trusted' => 'boolean', 'revoked' => 'boolean'];

    protected $hidden = ['client_secret'];

    /** Create a new client, returning [$client, $plaintextSecret] — the secret is shown once. */
    public static function generate(string $name, string $redirectUri, bool $trusted = false): array
    {
        $clientId = 'client_' . Str::random(24);
        $secret = Str::random(40);

        $client = static::create([
            'name'          => $name,
            'client_id'     => $clientId,
            'client_secret' => Hash::make($secret),
            'redirect_uri'  => $redirectUri,
            'trusted'       => $trusted,
        ]);

        return [$client, $secret];
    }

    public function checkSecret(string $secret): bool
    {
        return Hash::check($secret, $this->client_secret);
    }

    /** A client may register several redirect URIs, one per line. */
    public function allowedRedirectUris(): array
    {
        return array_filter(array_map('trim', explode("\n", $this->redirect_uri)));
    }

    public function redirectUriAllowed(string $uri): bool
    {
        return in_array(rtrim($uri, '/'), array_map(fn ($u) => rtrim($u, '/'), $this->allowedRedirectUris()), true);
    }
}
