@extends('layouts.app')
@section('title', 'Academic Structure')
@section('page-title', 'Academic Structure')

@section('content')
<p class="text-muted">Assign the <strong>Head of Department</strong> for each department and the <strong>Dean</strong> for each school. HODs only see admission applications for the departments they head, so this mapping must be set for the review workflow to route correctly.</p>

@if($hods->isEmpty())
    <div class="alert alert-warning small"><i class="fas fa-triangle-exclamation me-1"></i> No users have the <strong>hod</strong> role yet. Assign the role under Users first, then set them here.</div>
@endif

@foreach($schools as $school)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2" style="background:linear-gradient(90deg, {{ brand('primary_color') }} 0%, {{ brand('accent_color') }} 100%); padding:.35rem .6rem; border-radius:.25rem;">
            <span class="fw-semibold text-white" >{{ $school->name }} <span class="text-white-50 small">({{ $school->code }})</span></span>
            <form method="POST" action="{{ route('admin.structure.school', $school) }}" class="d-flex align-items-center gap-2">
                @csrf
                <!-- <label class="small text-muted mb-0">Dean:</label> -->
                 <label class="small text-white mb-0">Dean:</label>
                <select name="dean_id" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                    <option value="">— none —</option>
                    @foreach($deans as $d)
                        <option value="{{ $d->id }}" {{ ($school->dean_id == $d->id) ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light"><tr><th class="ps-3">Department</th><th style="width:45%">Head of Department</th></tr></thead>
                <tbody>
                    @forelse($school->departments as $dept)
                        <tr>
                            <td class="ps-3">{{ $dept->name }} <span class="text-muted small">({{ $dept->code }})</span></td>
                            <td>
                                <form method="POST" action="{{ route('admin.structure.department', $dept) }}" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <select name="hod_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">— none —</option>
                                        @foreach($hods as $h)
                                            <option value="{{ $h->id }}" {{ ($dept->hod_id == $h->id) ? 'selected' : '' }}>{{ $h->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($dept->hod)<span class="badge bg-success"><i class="fas fa-check"></i></span>@else<span class="badge bg-secondary">unset</span>@endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted text-center py-3">No departments.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endforeach
@endsection
