@extends('layouts.app')
@section('title', 'My Fee Statement')
@section('page-title', 'Fee Statement')

@section('content')
@php
    $billed = $student->feeInvoices->sum('amount');
    $paid = $student->feeInvoices->sum('amount_paid');
    $balance = $student->fee_balance;
@endphp
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Total billed</div><div class="fs-5 fw-bold">KES {{ number_format($billed) }}</div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Total paid</div><div class="fs-5 fw-bold text-success">KES {{ number_format($paid) }}</div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Outstanding</div><div class="fs-5 fw-bold {{ $balance > 0 ? 'text-danger' : 'text-success' }}">KES {{ number_format($balance) }}</div></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-4"><div class="card-header bg-white fw-semibold">Invoices</div>
<div class="card-body p-0">
    <table class="table mb-0 align-middle">
        <thead class="table-light"><tr><th class="ps-3">Invoice</th><th>Description</th><th>Year/Sem</th><th>Amount</th><th>Paid</th><th>Balance</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($student->feeInvoices as $inv)
                <tr>
                    <td class="ps-3 font-monospace small">{{ $inv->invoice_no }}</td>
                    <td>{{ $inv->description }}</td>
                    <td class="text-muted">{{ $inv->academic_year }} S{{ $inv->semester }}</td>
                    <td>KES {{ number_format($inv->amount) }}</td>
                    <td class="text-success">KES {{ number_format($inv->amount_paid) }}</td>
                    <td class="{{ $inv->balance > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">KES {{ number_format($inv->balance) }}</td>
                    <td><span class="badge {{ $inv->status === 'paid' ? 'bg-success' : ($inv->status === 'waived' ? 'bg-secondary' : 'bg-warning text-dark') }}">{{ $inv->status_label }}</span></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No invoices on your account.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>

<div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Payment history</div>
<div class="card-body p-0">
    <table class="table mb-0 align-middle">
        <thead class="table-light"><tr><th class="ps-3">Receipt</th><th>Date</th><th>Method</th><th>Reference</th><th class="text-end pe-3">Amount</th></tr></thead>
        <tbody>
            @forelse($student->feePayments as $p)
                <tr><td class="ps-3 font-monospace small">{{ $p->receipt_no }}</td><td>{{ $p->paid_on->format('d M Y') }}</td><td class="text-capitalize">{{ $p->method }}</td><td class="text-muted">{{ $p->reference ?? '—' }}</td><td class="text-end pe-3 fw-semibold text-success">KES {{ number_format($p->amount) }}</td></tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No payments recorded.</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div>
@endsection
