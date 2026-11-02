@extends('layouts.app')
@section('title', 'Allocate Supervisor')
@section('page-title', 'Allocate Supervisor')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <a href="{{ route('admin.theses') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
        <h6 class="mb-0 fw-semibold">Assign Supervisor</h6>
    </div>
    <div class="card-body p-4">

        {{-- Thesis Info --}}
        <div class="border rounded p-3 bg-light mb-4">
            <div class="fw-semibold mb-1">{{ $thesis->title }}</div>
            <div class="text-muted small">
                Student: {{ $thesis->student->user->name }} &bull; {{ $thesis->student->reg_no }}
            </div>
            @php
            $stageColors=['concept_development'=>'secondary','proposal_development'=>'info','ethical_approval'=>'warning','data_collection'=>'primary','writing_thesis'=>'info','intent_to_submit'=>'warning','submitted'=>'primary','examination'=>'warning','graduated'=>'success'];
            @endphp
            <span class="badge bg-{{ $stageColors[$thesis->current_stage] ?? 'secondary' }} mt-1">{{ $thesis->stage_label }}</span>
        </div>

        <form method="POST" action="{{ route('admin.thesis.allocate-supervisor', $thesis) }}">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-medium">Select Supervisor <span class="text-danger">*</span></label>
                <select name="supervisor_id" class="form-select @error('supervisor_id') is-invalid @enderror" required>
                    <option value="">Choose a supervisor…</option>
                    @foreach($supervisors as $sup)
                    <option value="{{ $sup->id }}" {{ old('supervisor_id') == $sup->id ? 'selected' : '' }}>
                        {{ $sup->user->name }} — {{ $sup->department }}
                        ({{ $sup->theses()->count() }}/{{ $sup->max_capacity }} students)
                    </option>
                    @endforeach
                </select>
                @error('supervisor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Supervisors at or above capacity are shown for reference. Verify availability before assigning.</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Assign Supervisor</button>
                <a href="{{ route('admin.theses') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
