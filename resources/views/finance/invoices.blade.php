@extends('layouts.app')
@section('title', 'Invoices')
@section('page-title', 'Fee Invoices')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Billed</div><div class="fs-5 fw-bold">KES {{ number_format($summary['billed']) }}</div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Collected</div><div class="fs-5 fw-bold text-success">KES {{ number_format($summary['collected']) }}</div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Outstanding</div><div class="fs-5 fw-bold text-danger">KES {{ number_format($summary['outstanding']) }}</div></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <form method="GET" class="d-flex gap-2">
            <input name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search student / invoice…">
            <select name="status" class="form-select form-select-sm" style="width:auto">
                <option value="">All statuses</option>
                @foreach(['unpaid','partial','paid','waived'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach
            </select>
            <button class="btn btn-sm btn-primary">Filter</button>
        </form>
        <a href="{{ route('finance.invoices.create') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus me-1"></i> New</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th class="ps-3">Invoice</th><th>Student</th><th>Description</th><th>Amount</th><th>Balance</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($invoices as $inv)
                    <tr>
                        <td class="ps-3 font-monospace small">{{ $inv->invoice_no }}</td>
                        <td>{{ $inv->student->user->name ?? '—' }}</td>
                        <td class="text-muted">{{ $inv->description }}</td>
                        <td>KES {{ number_format($inv->amount) }}</td>
                        <td class="{{ $inv->balance > 0 ? 'text-danger fw-semibold' : 'text-muted' }}">KES {{ number_format($inv->balance) }}</td>
                        <td><span class="badge {{ $inv->status === 'paid' ? 'bg-success' : ($inv->status === 'waived' ? 'bg-secondary' : 'bg-warning text-dark') }}">{{ $inv->status_label }}</span></td>
                        <td class="text-end pe-3"><a href="{{ route('finance.invoices.show', $inv) }}" class="btn btn-sm btn-link">Open</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No invoices found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $invoices->links() }}</div>
</div>
@endsection
