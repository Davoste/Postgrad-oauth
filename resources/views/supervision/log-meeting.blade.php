@extends('layouts.app')
@section('title', 'Log Meeting')
@section('page-title', 'Log Supervision Meeting')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <a href="{{ route('supervision.meetings') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
        <h6 class="mb-0 fw-semibold">Log a Supervision Meeting</h6>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('meetings.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-medium">Student <span class="text-danger">*</span></label>
                <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                    <option value="">Select student…</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->user->name }} ({{ $student->reg_no }})
                        </option>
                    @endforeach
                </select>
                @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-medium">Meeting Date <span class="text-danger">*</span></label>
                    <input type="date" name="meeting_date" value="{{ old('meeting_date') }}"
                           class="form-control @error('meeting_date') is-invalid @enderror"
                           max="{{ now()->format('Y-m-d') }}" required>
                    @error('meeting_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-7">
                    <label class="form-label fw-medium">Agenda <span class="text-danger">*</span></label>
                    <input type="text" name="agenda" value="{{ old('agenda') }}"
                           class="form-control @error('agenda') is-invalid @enderror"
                           placeholder="Primary discussion focus" required maxlength="500">
                    @error('agenda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Minutes &amp; Action Items <span class="text-danger">*</span></label>
                <textarea name="minutes" rows="6"
                          class="form-control @error('minutes') is-invalid @enderror"
                          placeholder="Detailed notes, decisions made, and next steps…" required>{{ old('minutes') }}</textarea>
                @error('minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>This meeting will be automatically signed by you. The student will be notified to sign off.</small>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Log &amp; Sign Meeting</button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
