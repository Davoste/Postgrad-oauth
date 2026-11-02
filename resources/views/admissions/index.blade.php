@extends('layouts.app')
@section('title', 'Admissions')
@section('page-title', 'Admissions')

@section('styles')
<style>
    @media (max-width: 768px) {
        .applications-table thead { display: none; }
        .applications-table,
        .applications-table tbody,
        .applications-table tr,
        .applications-table td { display: block; width: 100%; }
        .applications-table tr {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        .applications-table tr:last-child { border-bottom: 0; }
        .applications-table td {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 0 !important;
            border: 0;
            text-align: right;
            overflow-wrap: anywhere;
        }
        .applications-table td::before {
            content: attr(data-label);
            flex: 0 0 82px;
            color: #6b7280;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-align: left;
            text-transform: uppercase;
        }
        .applications-table td:last-child { justify-content: flex-end; padding-top: 8px !important; }
        .applications-table td:last-child::before { display: none; }
        .applications-table .status-cell { align-items: center; }
    }
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold">Applications</span>
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach(['submitted','under_review','offer_made','accepted','declined','rejected'] as $s)
                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle applications-table">
            <thead class="table-light"><tr><th class="ps-3">Ref</th><th>Applicant</th><th>Programme</th><th>Submitted</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($applications as $a)
                    <tr>
                        <td data-label="Ref" class="ps-3 font-monospace small">{{ $a->ref_no }}</td>
                        <td data-label="Applicant">{{ $a->full_name }}</td>
                        <td data-label="Programme" class="text-muted">{{ $a->programme->name }}</td>
                        <td data-label="Submitted" class="text-muted small">{{ $a->created_at->format('d M Y') }}</td>
                        <td data-label="Status" class="status-cell"><span class="badge {{ $a->status === 'accepted' ? 'bg-success' : ($a->status === 'rejected' || $a->status==='declined' ? 'bg-secondary' : 'bg-warning text-dark') }}">{{ $a->status_label }}</span></td>
                        <td data-label="" class="text-end pe-3"><a href="{{ route('admissions.show', $a) }}" class="btn btn-sm btn-link">Review</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No applications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $applications->links() }}</div>
</div>
@endsection
