<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThesisReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'submission_id',
        'reviewer_id',
        'review_type',
        'stage',
        'rating',
        'strengths',
        'weaknesses',
        'recommendations',
        'decision',
        'conditions',
        'requires_meeting',
        'reviewed_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'conditions' => 'array',
        'requires_meeting' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ThesisSubmission::class, 'submission_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function scopeByStage($query, $stage)
    {
        return $query->where('stage', $stage);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('review_type', $type);
    }

    public function scopeByDecision($query, $decision)
    {
        return $query->where('decision', $decision);
    }

    public function scopeRequiresMeeting($query)
    {
        return $query->where('requires_meeting', true);
    }
}