<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class FeeInvoice extends Model
{
    protected $fillable = [
        'invoice_no', 'student_id', 'academic_year', 'semester', 'description',
        'amount', 'amount_paid', 'due_date', 'status', 'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (FeeInvoice $i) {
            $i->invoice_no = $i->invoice_no ?: ('INV/' . date('Y') . '/' . str_pad((string) ((static::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT));
        });
    }

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function payments(): HasMany { return $this->hasMany(FeePayment::class); }

    public function getBalanceAttribute(): float
    {
        return max(0, (float) $this->amount - (float) $this->amount_paid);
    }

    public function recalcStatus(): void
    {
        $paid = (float) $this->payments()->sum('amount');
        $this->amount_paid = $paid;
        if ($this->status !== 'waived') {
            if ($paid <= 0) $this->status = 'unpaid';
            elseif ($paid < (float) $this->amount) $this->status = 'partial';
            else $this->status = 'paid';
        }
        $this->save();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'unpaid' => 'Unpaid', 'partial' => 'Partially paid',
            'paid' => 'Paid in full', 'waived' => 'Waived',
            default => ucfirst($this->status),
        };
    }
}
