@extends('layouts.app')
@section('title', 'Programmes')
@section('page-title', 'Manage Programmes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">Add, edit or remove the postgraduate programmes offered.</p>
    <a href="{{ route('admin.programmes.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New programme</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th class="ps-3">Programme</th><th>School / Dept</th><th>Level</th><th>Yrs</th><th>Fees/yr</th><th>Status</th><th class="text-end pe-3"></th></tr>
            </thead>
            <tbody>
                @forelse($programmes as $p)
                    <tr>
                        <td class="ps-3"><span class="fw-semibold">{{ $p->name }}</span><div class="text-muted small font-monospace">{{ $p->code }}</div></td>
                        <td class="small text-muted">{{ optional($p->department->school)->code }} · {{ optional($p->department)->name }}</td>
                        <td><span class="badge bg-secondary">{{ $p->level_label }}</span></td>
                        <td>{{ $p->duration_years }}</td>
                        <td class="text-nowrap">KES {{ number_format($p->tuition_per_year) }}</td>
                        <td>
                            @if($p->is_active)<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Inactive</span>@endif
                            @if($p->applications_open)<span class="badge bg-info">Open</span>@else<span class="badge bg-warning text-dark">Closed</span>@endif
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.programmes.edit', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('admin.programmes.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete this programme?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No programmes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $programmes->links('pagination::bootstrap-4') }}</div>
</div>
@endsection
