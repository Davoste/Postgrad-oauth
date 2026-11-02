<?php

namespace App\Models\OAuth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OAuthAuthCode extends Model
{
    protected $table = 'oauth_auth_codes';

    protected $fillable = ['code', 'user_id', 'oauth_client_id', 'redirect_uri', 'scopes', 'expires_at', 'revoked'];

    protected $casts = ['expires_at' => 'datetime', 'revoked' => 'boolean'];

    public function user(): BelongsTo { return $this->belongsTo(\App\Models\User::class); }
    public function client(): BelongsTo { return $this->belongsTo(OAuthClient::class, 'oauth_client_id'); }

    public static function issue(int $userId, int $clientId, string $redirectUri, ?string $scopes): self
    {
        return static::create([
            'code'            => Str::random(64),
            'user_id'         => $userId,
            'oauth_client_id' => $clientId,
            'redirect_uri'    => $redirectUri,
            'scopes'          => $scopes,
            'expires_at'      => now()->addMinutes(10),
        ]);
    }

    public function isValid(): bool
    {
        return !$this->revoked && $this->expires_at->isFuture();
    }
}
