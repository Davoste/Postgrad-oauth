<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThesisMilestone extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'stage',
        'milestone_name',
        'description',
        'target_date',
        'completed_date',
        'status',
        'progress_percentage',
        'checklist_items',
    ];

    protected $casts = [
        'target_date' => 'date',
        'completed_date' => 'date',
        'progress_percentage' => 'decimal:2',
        'checklist_items' => 'array',
    ];

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }

    public function scopeByStage($query, $stage)
    {
        return $query->where('stage', $stage);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
                    ->where('target_date', '<', now());
    }

    public function scopeUpcoming($query, $days = 7)
    {
        return $query->where('status', '!=', 'completed')
                    ->where('target_date', '<=', now()->addDays($days))
                    ->where('target_date', '>=', now());
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_date' => now(),
            'progress_percentage' => 100,
        ]);
    }

    public function updateProgress($percentage)
    {
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
            'status' => $percentage >= 100 ? 'completed' : 'in_progress',
            'completed_date' => $percentage >= 100 ? now() : null,
        ]);
    }
}