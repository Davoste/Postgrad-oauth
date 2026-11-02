@extends('layouts.app')
@section('title', $programme->exists ? 'Edit Programme' : 'New Programme')
@section('page-title', $programme->exists ? 'Edit Programme' : 'New Programme')

@section('content')
<div class="row justify-content-center"><div class="col-lg-9">
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="card border-0 shadow-sm"><div class="card-body p-4">
    <form method="POST" action="{{ $programme->exists ? route('admin.programmes.update', $programme) : route('admin.programmes.store') }}">
        @csrf
        @if($programme->exists) @method('PUT') @endif
        @php $modes = old('modes', $programme->modes ?? []); @endphp

        <div class="row g-3">
            <div class="col-md-8"><label class="form-label small fw-semibold">Programme name *</label>
                <input name="name" value="{{ old('name', $programme->name) }}" required class="form-control"></div>
            <div class="col-md-4"><label class="form-label small fw-semibold">Code *</label>
                <input name="code" value="{{ old('code', $programme->code) }}" required class="form-control font-monospace" placeholder="e.g. MSC-CS"></div>

            <div class="col-md-6"><label class="form-label small fw-semibold">Department *</label>
                <select name="department_id" required class="form-select">
                    <option value="">Select…</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ (old('department_id', $programme->department_id) == $d->id) ? 'selected' : '' }}>{{ optional($d->school)->code }} — {{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label small fw-semibold">Level *</label>
                <select name="level" required class="form-select">
                    @foreach(['masters'=>"Master's", 'phd'=>'PhD', 'pgd'=>'Postgraduate Diploma'] as $val=>$lab)
                        <option value="{{ $val }}" {{ (old('level', $programme->level) === $val) ? 'select' : ''}}>{{ $lab }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label small fw-semibold">Duration (years) *</label>
                <input type="number" min="1" max="8" name="duration_years" value="{{ old('duration_years', $programme->duration_years) }}" required class="form-control"></div>

            <div class="col-md-4"><label class="form-label small fw-semibold">Tuition per year (KES) *</label>
                <input type="number" step="0.01" min="0" name="tuition_per_year" value="{{ old('tuition_per_year', $programme->tuition_per_year) }}" required class="form-control"></div>

            <div class="col-md-8"><label class="form-label small fw-semibold">Modes of study</label>
                <div class="d-flex flex-wrap gap-3 pt-2">
                    @foreach(['full_time'=>'Full time','part_time'=>'Part time','school_based'=>'School based','weekend_intensive'=>'Weekend / intensive'] as $val=>$lab)
                        <div class="form-check">
                            <input type="checkbox" name="modes[]" value="{{ $val }}" id="mode_{{ $val }}" class="form-check-input" {{ in_array($val, $modes) ? 'checked' : ''}}>
                            <label for="mode_{{ $val }}" class="form-check-label small">{{ $lab }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-12"><label class="form-label small fw-semibold">Overview</label>
                <textarea name="overview" rows="3" class="form-control">{{ old('overview', $programme->overview) }}</textarea></div>
            <div class="col-12"><label class="form-label small fw-semibold">Career prospects</label>
                <textarea name="career_prospects" rows="2" class="form-control">{{ old('career_prospects', $programme->career_prospects) }}</textarea></div>

            <div class="col-md-6">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" {{ old('is_active', $programme->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Active (visible in catalogue)</label>
                </div>
                <div class="form-check form-switch">
                    <input type="checkbox" name="applications_open" value="1" id="applications_open" class="form-check-input" {{ old('applications_open', $programme->applications_open) ? 'checked' : ''}}>
                    <label for="applications_open" class="form-check-label">Applications open</label>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary"><i class="fas fa-save me-1"></i> {{ $programme->exists ? 'Save changes' : 'Create programme' }}</button>
            <a href="{{ route('admin.programmes.index') }}" class="btn btn-link">Cancel</a>
        </div>
    </form>
</div></div>
</div></div>
@endsection
