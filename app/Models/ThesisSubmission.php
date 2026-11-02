<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThesisSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'stage',
        'submission_type',
        'title',
        'description',
        'documents',
        'status',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'documents' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(ThesisFeedback::class, 'submission_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ThesisReview::class, 'submission_id');
    }

    public function scopeByStage($query, $stage)
    {
        return $query->where('stage', $stage);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}