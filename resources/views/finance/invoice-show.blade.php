@extends('layouts.app')
@section('title', 'Invoice')
@section('page-title', $invoice->invoice_no)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start">
                <h5 class="fw-semibold mb-0">Invoice details</h5>
                <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'waived' ? 'bg-secondary' : 'bg-warning text-dark') }}">{{ $invoice->status_label }}</span>
            </div>
            <hr>
            <dl class="row mb-0">
                <dt class="col-sm-4 text-muted fw-normal">Student</dt><dd class="col-sm-8">{{ $invoice->student->user->name ?? '—' }} ({{ $invoice->student->reg_no }})</dd>
                <dt class="col-sm-4 text-muted fw-normal">Description</dt><dd class="col-sm-8">{{ $invoice->description }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Year / Semester</dt><dd class="col-sm-8">{{ $invoice->academic_year }} · S{{ $invoice->semester }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Amount</dt><dd class="col-sm-8">KES {{ number_format($invoice->amount) }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Paid</dt><dd class="col-sm-8 text-success">KES {{ number_format($invoice->amount_paid) }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Balance</dt><dd class="col-sm-8 fw-semibold {{ $invoice->balance > 0 ? 'text-danger' : '' }}">KES {{ number_format($invoice->balance) }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Due</dt><dd class="col-sm-8">{{ optional($invoice->due_date)->format('d M Y') ?? '—' }}</dd>
            </dl>
        </div></div>

        <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Payments</div>
        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <thead class="table-light"><tr><th class="ps-3">Receipt</th><th>Date</th><th>Method</th><th>By</th><th class="text-end pe-3">Amount</th></tr></thead>
                <tbody>
                    @forelse($invoice->payments as $p)
                        <tr><td class="ps-3 font-monospace small">{{ $p->receipt_no }}</td><td>{{ $p->paid_on->format('d M Y') }}</td><td class="text-capitalize">{{ $p->method }}</td><td class="text-muted">{{ $p->recordedBy->name ?? '—' }}</td><td class="text-end pe-3 fw-semibold text-success">KES {{ number_format($p->amount) }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No payments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div></div>
    </div>

    <div class="col-lg-4">
        @if(!in_array($invoice->status, ['paid','waived']))
        <div class="card border-0 shadow-sm"><div class="card-body p-4">
            <h6 class="fw-semibold mb-3">Record payment</h6>
            <form method="POST" action="{{ route('finance.invoices.payment', $invoice) }}">
                @csrf
                <div class="mb-2"><label class="form-label small">Amount (KES)</label><input type="number" step="0.01" name="amount" value="{{ $invoice->balance }}" class="form-control form-control-sm" required></div>
                <div class="mb-2"><label class="form-label small">Method</label><select name="method" class="form-select form-select-sm"><option value="bank">Bank</option><option value="mpesa">M-Pesa</option><option value="cheque">Cheque</option><option value="cash">Cash</option><option value="other">Other</option></select></div>
                <div class="mb-2"><label class="form-label small">Reference</label><input name="reference" class="form-control form-control-sm"></div>
                <div class="mb-3"><label class="form-label small">Date paid</label><input type="date" name="paid_on" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required></div>
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-check me-1"></i> Record payment</button>
            </form>
            <form method="POST" action="{{ route('finance.invoices.waive', $invoice) }}" class="mt-2" onsubmit="return confirm('Waive this invoice?')">
                @csrf
                <button class="btn btn-outline-secondary btn-sm w-100">Waive invoice</button>
            </form>
        </div></div>
        @endif
    </div>
</div>
@endsection
