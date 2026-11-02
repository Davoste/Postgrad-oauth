<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThesisFeedback extends Model
{
    use HasFactory;

    protected $table = 'thesis_feedbacks';

    protected $fillable = [
        'thesis_id',
        'submission_id',
        'reviewer_id',
        'stage',
        'comments',
        'decision',
        'action_items',
        'deadline',
        'is_final',
    ];

    protected $casts = [
        'action_items' => 'array',
        'deadline' => 'date',
        'is_final' => 'boolean',
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

    public function scopeFinal($query)
    {
        return $query->where('is_final', true);
    }

    public function scopeByDecision($query, $decision)
    {
        return $query->where('decision', $decision);
    }
}