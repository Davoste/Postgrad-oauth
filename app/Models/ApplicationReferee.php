<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApplicationReferee extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'ratings' => 'array',
        'submitted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (ApplicationReferee $referee) {
            $referee->token = $referee->token ?: Str::random(64);
        });
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}