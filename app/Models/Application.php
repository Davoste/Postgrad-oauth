<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Support\Str;

class Application extends Model
{
    protected $table = 'admission_applications';
    protected $guarded = ['id'];

    protected $casts = [
        'dob'                  => 'date',
        'referees'             => 'array',
        'documents'            => 'array',
        'employment'           => 'array',
        'commencement_date'    => 'date',
        'completion_date'      => 'date',
        'hod_reviewed_at'      => 'datetime',
        'director_reviewed_at' => 'datetime',
        'submitted_at'         => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Application $a) {
            $a->ref_no = $a->ref_no ?: ('BPS/AF/' . date('Y') . '/' . str_pad((string) ((static::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT));
            $a->tracking_token = $a->tracking_token ?: Str::random(40);
        });
    }

    public function programme(): BelongsTo { return $this->belongsTo(Programme::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function hod(): BelongsTo { return $this->belongsTo(User::class, 'hod_id'); }
    public function director(): BelongsTo { return $this->belongsTo(User::class, 'director_id'); }
    public function acceptanceForm(): HasOne { return $this->hasOne(AcceptanceForm::class); }
    public function applicationReferees(): HasMany { return $this->hasMany(ApplicationReferee::class); }
    public function student(): HasOne { return $this->hasOne(Student::class); }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->surname} {$this->first_name} {$this->other_names}");
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted'    => 'Submitted',
            'under_review' => 'Under review',
            'offer_made'   => 'Offer made',
            'accepted'     => 'Accepted / enrolled',
            'declined'     => 'Offer declined',
            'rejected'     => 'Not successful',
            default        => ucfirst($this->status),
        };
    }
}
