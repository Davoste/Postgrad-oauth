<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphMany};

class Student extends Model {
    protected $fillable = [
        'user_id', 'reg_no', 'program', 'admission_year',
        'application_id', 'programme_id', 'mode', 'status',
        'moodle_user_id', 'moodle_synced_at',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function thesis() { return $this->hasOne(Thesis::class); }
    public function meetings() { return $this->hasMany(SupervisionMeeting::class, 'student_id'); }

    // Admissions linkage
    public function application(): BelongsTo { return $this->belongsTo(Application::class); }
    public function programme(): BelongsTo { return $this->belongsTo(Programme::class); }

    // Institutional additions (finance / documents)
    public function feeInvoices(): HasMany { return $this->hasMany(FeeInvoice::class)->latest(); }
    public function feePayments(): HasMany { return $this->hasMany(FeePayment::class)->latest('paid_on'); }
    public function documents(): MorphMany { return $this->morphMany(Document::class, 'documentable'); }

    public function getFeeBalanceAttribute(): float
    {
        return (float) $this->feeInvoices()->whereIn('status', ['unpaid', 'partial'])->get()->sum(fn ($i) => $i->balance);
    }
}
