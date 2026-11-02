<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SupervisionMeeting extends Model
{
    use LogsActivity;

    protected $fillable = [
        'thesis_id',
        'student_id',
        'supervisor_id',
        'meeting_date',
        'agenda',
        'minutes',
        'signed_student',
        'signed_supervisor',
        'student_sign_ip',
        'supervisor_sign_ip',
    ];

    protected $casts = [
        'meeting_date'     => 'date',
        'signed_student'   => 'boolean',
        'signed_supervisor'=> 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['signed_student', 'signed_supervisor', 'meeting_date'])
            ->logOnlyDirty();
    }

    public function thesis(): BelongsTo
    {
        return $this->belongsTo(Thesis::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    /** True if both parties have signed. */
    public function isFullySigned(): bool
    {
        return $this->signed_student && $this->signed_supervisor;
    }
}
