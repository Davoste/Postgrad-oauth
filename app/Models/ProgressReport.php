<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProgressReport extends Model
{
    use LogsActivity;

    protected $fillable = [
        'thesis_id',
        'supervisor_id',
        'report_type',
        'period_start',
        'period_end',
        'content',
        'student_progress_rating',
        'challenges',
        'recommendations',
        'submitted_at',
        'student_acknowledged_at',
    ];

    protected $casts = [
        'period_start'             => 'date',
        'period_end'               => 'date',
        'submitted_at'             => 'datetime',
        'student_acknowledged_at'  => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['report_type', 'student_progress_rating', 'student_acknowledged_at'])
            ->logOnlyDirty();
    }

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function isAcknowledged(): bool
    {
        return $this->student_acknowledged_at !== null;
    }

    public function scopeQuarterly($query)
    {
        return $query->where('report_type', 'quarterly');
    }

    public function scopeBiannual($query)
    {
        return $query->where('report_type', 'biannual');
    }
}
