<?php

namespace App\Models\OAuth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OAuthRefreshToken extends Model
{
    protected $table = 'oauth_refresh_tokens';

    protected $fillable = ['token_hash', 'oauth_access_token_id', 'expires_at', 'revoked'];

    protected $casts = ['expires_at' => 'datetime', 'revoked' => 'boolean'];

    public function accessToken(): BelongsTo { return $this->belongsTo(OAuthAccessToken::class, 'oauth_access_token_id'); }

    public static function issue(int $accessTokenId, int $ttlDays = 30): array
    {
        $plain = Str::random(80);

        $token = static::create([
            'token_hash'            => hash('sha256', $plain),
            'oauth_access_token_id' => $accessTokenId,
            'expires_at'            => now()->addDays($ttlDays),
        ]);

        return [$token, $plain];
    }

    public static function findValidByPlainToken(string $plain): ?self
    {
        $token = static::where('token_hash', hash('sha256', $plain))->first();

        return ($token && !$token->revoked && $token->expires_at->isFuture()) ? $token : null;
    }
}
