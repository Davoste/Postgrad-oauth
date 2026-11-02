<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeePayment extends Model
{
    protected $fillable = [
        'receipt_no', 'fee_invoice_id', 'student_id', 'amount', 'method',
        'reference', 'paid_on', 'recorded_by', 'notes',
    ];

    protected $casts = ['amount' => 'decimal:2', 'paid_on' => 'date'];

    protected static function booted(): void
    {
        static::creating(function (FeePayment $p) {
            $p->receipt_no = $p->receipt_no ?: ('RCP/' . date('Y') . '/' . str_pad((string) ((static::max('id') ?? 0) + 1), 5, '0', STR_PAD_LEFT));
        });
    }

    public function invoice(): BelongsTo { return $this->belongsTo(FeeInvoice::class, 'fee_invoice_id'); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function recordedBy(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by'); }
}
