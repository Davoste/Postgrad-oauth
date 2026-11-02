<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Supervisor extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'department', 'max_capacity'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** All theses assigned to this supervisor. */
    public function theses(): HasMany
    {
        return $this->hasMany(Thesis::class);
    }

    /** All students whose thesis is assigned to this supervisor. */
    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,  // final model
            Thesis::class,   // intermediate model
            'supervisor_id', // FK on theses → supervisors
            'id',            // FK on students (local key target)
            'id',            // local key on supervisors
            'student_id'     // FK on theses → students
        );
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(SupervisionMeeting::class);
    }

    public function progressReports(): HasMany
    {
        return $this->hasMany(ProgressReport::class);
    }

    /** Meetings logged in the given month (default: last month). */
    public function meetingsInMonth(int $year, int $month): int
    {
        return $this->meetings()
            ->whereYear('meeting_date', $year)
            ->whereMonth('meeting_date', $month)
            ->count();
    }

    /** Whether this supervisor is at or over their student capacity. */
    public function isAtCapacity(): bool
    {
        return $this->theses()->whereNotIn('state', ['graduated'])->count() >= $this->max_capacity;
    }
}
