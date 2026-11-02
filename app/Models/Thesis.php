<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\ModelStates\HasStates;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\States\ThesisState;

class Thesis extends Model
{
    use HasFactory, HasStates, LogsActivity;

    protected $table = 'theses';

    protected $fillable = [
        'student_id',
        'supervisor_id',
        'title',
        'state',
        'progress_percentage',
        'intent_to_submit_date',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'state'                 => ThesisState::class,
        'intent_to_submit_date' => 'date',
        'progress_percentage'   => 'decimal:2',
        'submitted_at'          => 'datetime',
        'graded_at'             => 'datetime',
    ];

    /** Ordered stage list — used for progress comparisons. */
    public const STAGE_ORDER = [
        'concept_development',
        'proposal_development',
        'ethical_approval',
        'data_collection',
        'writing_thesis',
        'intent_to_submit',
        'submitted',
        'examination',
        'graduated',
    ];

    public const STAGE_LABELS = [
        'concept_development'  => 'Concept Development',
        'proposal_development' => 'Proposal Development',
        'ethical_approval'     => 'Ethical Clearance',
        'data_collection'      => 'Data Collection',
        'writing_thesis'       => 'Thesis Writing',
        'intent_to_submit'     => 'Intent to Submit',
        'submitted'            => 'Submitted',
        'examination'          => 'Examination',
        'graduated'            => 'Graduated',
    ];

    public const STAGE_DESCRIPTIONS = [
        'concept_development'  => 'Develop and submit your thesis concept for supervisor review.',
        'proposal_development' => 'Create a detailed research proposal and submit for approval.',
        'ethical_approval'     => 'Obtain ethical clearance from the ethics committee.',
        'data_collection'      => 'Collect and analyse your research data.',
        'writing_thesis'       => 'Write and finalise your thesis document.',
        'intent_to_submit'     => 'Declare your intent to submit and confirm your submission date.',
        'submitted'            => 'Thesis submitted for examination.',
        'examination'          => 'Thesis under examination by appointed examiners.',
        'graduated'            => 'Thesis completed — congratulations!',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ThesisSubmission::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(ThesisFeedback::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(ThesisMilestone::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ThesisReview::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(SupervisionMeeting::class);
    }

    public function progressReports(): HasMany
    {
        return $this->hasMany(ProgressReport::class);
    }

    // ── Activity Log ─────────────────────────────────────────────────────────

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['state', 'supervisor_id', 'progress_percentage', 'title'])
            ->logOnlyDirty();
    }

    // ── Computed Attributes ──────────────────────────────────────────────────

    /** Returns the string stage name, e.g. 'proposal_development'. */
    public function getCurrentStageAttribute(): string
    {
        return (string) $this->state;
    }

    /** Returns the human-readable stage label. */
    public function getStageLabelAttribute(): string
    {
        return self::STAGE_LABELS[$this->current_stage] ?? ucfirst(str_replace('_', ' ', $this->current_stage));
    }

    /** Integer position (0-indexed) in STAGE_ORDER. */
    public function getStageIndexAttribute(): int
    {
        return array_search($this->current_stage, self::STAGE_ORDER, true) ?: 0;
    }

    // ── State Machine ────────────────────────────────────────────────────────

    /** Map string stage names to their State class FQN. */
    private static array $stateClasses = [
        'concept_development'  => \App\Models\States\ConceptDevelopment::class,
        'proposal_development' => \App\Models\States\ProposalDevelopment::class,
        'ethical_approval'     => \App\Models\States\EthicalClearance::class,
        'data_collection'      => \App\Models\States\DataCollection::class,
        'writing_thesis'       => \App\Models\States\ThesisWriting::class,
        'intent_to_submit'     => \App\Models\States\IntentToSubmit::class,
        'submitted'            => \App\Models\States\Submitted::class,
        'examination'          => \App\Models\States\Examination::class,
        'graduated'            => \App\Models\States\Graduated::class,
    ];

    public function canTransitionTo(string $newStage): bool
    {
        $class = self::$stateClasses[$newStage] ?? null;
        if (!$class) return false;
        return $this->state->canTransitionTo($class);
    }

    public function transitionTo(string $newStage): bool
    {
        $class = self::$stateClasses[$newStage] ?? null;
        if (!$class || !$this->state->canTransitionTo($class)) {
            return false;
        }
        $this->state->transitionTo($class);
        $this->updateProgressForStage($newStage);
        return true;
    }

    private function updateProgressForStage(string $stage): void
    {
        $stageProgress = [
            'concept_development'  => 10,
            'proposal_development' => 25,
            'ethical_approval'     => 40,
            'data_collection'      => 55,
            'writing_thesis'       => 70,
            'intent_to_submit'     => 85,
            'submitted'            => 95,
            'examination'          => 98,
            'graduated'            => 100,
        ];
        $this->update(['progress_percentage' => $stageProgress[$stage] ?? 0]);
    }

    /** True if the thesis has passed (or is at) the given stage. */
    public function isAtOrPast(string $stage): bool
    {
        $currentIdx = array_search($this->current_stage, self::STAGE_ORDER, true);
        $targetIdx  = array_search($stage, self::STAGE_ORDER, true);
        return $currentIdx !== false && $targetIdx !== false && $currentIdx >= $targetIdx;
    }

    // ── Query Helpers ────────────────────────────────────────────────────────

    public function getLatestSubmissionForStage(string $stage): ?ThesisSubmission
    {
        return $this->submissions()
            ->where('stage', $stage)
            ->latest('submitted_at')
            ->first();
    }

    public function getLatestFeedbackForStage(string $stage): ?ThesisFeedback
    {
        return $this->feedbacks()
            ->where('stage', $stage)
            ->latest()
            ->first();
    }

    public function getPendingMilestones()
    {
        return $this->milestones()
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('target_date')
            ->get();
    }

    public function getOverdueMilestones()
    {
        return $this->milestones()
            ->where('status', '!=', 'completed')
            ->where('target_date', '<', now()->toDateString())
            ->get();
    }

    public function getMeetingsThisMonth()
    {
        return $this->meetings()
            ->whereYear('meeting_date', now()->year)
            ->whereMonth('meeting_date', now()->month)
            ->count();
    }
}
