<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, CausesActivity, MustVerifyEmail;

    protected $fillable = [
        'name', 'surname', 'first_name', 'email', 'password', 'email_verified_at',
        'phone', 'staff_no', 'id_no', 'is_active', 'last_login_at',
        'avatar_path', 'title', 'bio', 'office',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    // Role helpers (thin wrappers over Spatie hasRole)
    public function isStudent(): bool         { return $this->hasRole('student'); }
    public function isSupervisor(): bool      { return $this->hasRole('supervisor'); }
    public function isHod(): bool             { return $this->hasRole('hod'); }
    public function isDean(): bool            { return $this->hasRole('dean'); }
    public function isDirector(): bool        { return $this->hasRole('director_bps'); }
    public function isFinance(): bool         { return $this->hasRole('finance'); }
    public function isRegistrar(): bool       { return $this->hasRole('registrar'); }
    public function isEthicsReviewer(): bool  { return $this->hasRole('ethics_reviewer'); }
    public function isCommittee(): bool       { return $this->hasRole('committee'); }
    public function isAdmin(): bool           { return $this->hasRole('admin'); }

    public function dashboardRoute(): string
    {
        return match (true) {
            $this->isAdmin()                => 'admin.dashboard',
            $this->isDirector()             => 'director.dashboard',
            $this->isFinance()              => 'finance.dashboard',
            $this->isRegistrar()            => 'registrar.dashboard',
            $this->isEthicsReviewer()       => 'ethics.pending',
            $this->isSupervisor()           => 'supervisor.dashboard',
            $this->isHod(), $this->isDean() => 'admin.monitor',
            $this->isStudent()              => 'student.dashboard',
            default                         => 'dashboard',
        };
    }

    // Relationships
    public function student(): HasOne    { return $this->hasOne(Student::class); }
    public function supervisor(): HasOne { return $this->hasOne(Supervisor::class); }
    public function documents(): MorphMany { return $this->morphMany(Document::class, 'documentable'); }
    public function applications(): HasMany { return $this->hasMany(Application::class); }

    /**
     * Whether this user has been successfully admitted (has an active
     * student record produced by the admissions flow). Used to gate the
     * portal sidebar for newly-registered users who haven't been admitted.
     */
    public function isAdmitted(): bool
    {
        return $this->student()->exists();
    }

    // Display helpers
    public function getDisplayNameAttribute(): string
    {
        return trim(($this->title ? $this->title . ' ' : '') . $this->name);
    }

    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/', trim($this->name)) ?: [];
        $first = $parts[0][0] ?? '';
        $last  = count($parts) > 1 ? end($parts)[0] : '';
        return strtoupper($first . $last);
    }
}
