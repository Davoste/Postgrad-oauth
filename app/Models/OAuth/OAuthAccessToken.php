<?php

namespace App\Models\OAuth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OAuthAccessToken extends Model
{
    protected $table = 'oauth_access_tokens';

    protected $fillable = ['token_hash', 'user_id', 'oauth_client_id', 'scopes', 'expires_at', 'revoked'];

    protected $casts = ['expires_at' => 'datetime', 'revoked' => 'boolean'];

    public function user(): BelongsTo { return $this->belongsTo(\App\Models\User::class); }
    public function client(): BelongsTo { return $this->belongsTo(OAuthClient::class, 'oauth_client_id'); }

    /** Issue a new access token; returns [$model, $plaintextToken]. */
    public static function issue(int $userId, int $clientId, ?string $scopes, int $ttlMinutes = 60): array
    {
        $plain = Str::random(80);

        $token = static::create([
            'token_hash'      => hash('sha256', $plain),
            'user_id'         => $userId,
            'oauth_client_id' => $clientId,
            'scopes'          => $scopes,
            'expires_at'      => now()->addMinutes($ttlMinutes),
        ]);

        return [$token, $plain];
    }

    public static function findValidByPlainToken(string $plain): ?self
    {
        $token = static::where('token_hash', hash('sha256', $plain))->first();

        return ($token && !$token->revoked && $token->expires_at->isFuture()) ? $token : null;
    }
}
