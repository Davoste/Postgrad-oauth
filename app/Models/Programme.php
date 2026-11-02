<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Programme extends Model
{
    protected $fillable = [
        'department_id', 'name', 'code', 'level', 'modes', 'duration_years',
        'tuition_per_year', 'overview', 'career_prospects', 'is_active', 'applications_open',
    ];

    protected $casts = [
        'modes' => 'array',
        'is_active' => 'boolean',
        'applications_open' => 'boolean',
    ];

    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function requirements(): HasMany { return $this->hasMany(ProgrammeRequirement::class)->orderBy('sort_order'); }

    public function getLevelLabelAttribute(): string
    {
        return match ($this->level) {
            'pgd' => 'Postgraduate Diploma',
            'masters' => "Master's",
            'phd' => 'Doctor of Philosophy',
            default => ucfirst($this->level),
        };
    }

    public function scopeActive($q) { return $q->where('is_active', true); }

    /** Total indicative cost across the programme duration. */
    public function getTotalCostAttribute(): float
    {
        return (float) $this->tuition_per_year * (int) $this->duration_years;
    }

    /**
     * Minimum entry requirements. Uses any explicitly-seeded requirements,
     * otherwise falls back to the standard JOOUST entry criteria for the level.
     */
    public function getMinRequirementsAttribute(): array
    {
        if ($this->relationLoaded('requirements') ? $this->requirements->isNotEmpty()
            : $this->requirements()->exists()) {
            return $this->requirements()->pluck('requirement')->all();
        }

        return match ($this->level) {
            'phd' => [
                "A relevant Master's degree from a recognised university",
                'A research concept note / proposal in the intended area',
                'Two academic referees',
            ],
            'pgd' => [
                "A relevant Bachelor's degree (or equivalent) from a recognised university",
                'Two academic referees',
            ],
            default => [
                "A relevant Bachelor's degree, Second Class (Upper Division) or equivalent",
                'Lower-division holders may qualify with relevant experience, subject to Senate approval',
                'Two academic referees',
            ],
        };
    }
}
