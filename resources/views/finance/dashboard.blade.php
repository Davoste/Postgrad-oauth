@extends('layouts.app')
@section('title', 'Finance Dashboard')
@section('page-title', 'Finance Office')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="fas fa-file-invoice-dollar text-primary fa-lg"></i></div>
            <div><div class="fs-5 fw-bold">KES {{ number_format($summary['billed']) }}</div><div class="text-muted small">Total billed</div></div>
        </div></div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-success bg-opacity-10 p-3"><i class="fas fa-coins text-success fa-lg"></i></div>
            <div><div class="fs-5 fw-bold">KES {{ number_format($summary['collected']) }}</div><div class="text-muted small">Collected</div></div>
        </div></div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-danger bg-opacity-10 p-3"><i class="fas fa-hand-holding-usd text-danger fa-lg"></i></div>
            <div><div class="fs-5 fw-bold">KES {{ number_format($summary['outstanding']) }}</div><div class="text-muted small">Outstanding</div></div>
        </div></div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
            <div class="rounded-circle bg-info bg-opacity-10 p-3"><i class="fas fa-receipt text-info fa-lg"></i></div>
            <div><div class="fs-4 fw-bold">{{ $summary['invoices'] }}</div><div class="text-muted small">Invoices</div></div>
        </div></div>
    </div>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="{{ route('finance.invoices') }}" class="btn btn-primary"><i class="fas fa-list me-1"></i> Manage invoices</a>
    <a href="{{ route('finance.invoices.create') }}" class="btn btn-outline-primary"><i class="fas fa-plus me-1"></i> New invoice</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Recent payments</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th class="ps-3">Student</th><th>Receipt</th><th>Date</th><th>Method</th><th class="text-end pe-3">Amount</th></tr></thead>
            <tbody>
                @forelse($recentPayments as $p)
                    <tr><td class="ps-3">{{ $p->student->user->name ?? '—' }}</td><td class="font-monospace small">{{ $p->receipt_no }}</td><td>{{ $p->paid_on->format('d M Y') }}</td><td class="text-capitalize">{{ $p->method }}</td><td class="text-end pe-3 fw-semibold text-success">KES {{ number_format($p->amount) }}</td></tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
