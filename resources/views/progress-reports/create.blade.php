@extends('layouts.app')
@section('title', 'New Progress Report')
@section('page-title', 'Submit Progress Report')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <a href="{{ route('progress-reports.index', $thesis) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
        <div>
            <h6 class="mb-0 fw-semibold">New Progress Report</h6>
            <small class="text-muted">{{ $thesis->title }}</small>
        </div>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('progress-reports.store', $thesis) }}" method="POST">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-medium">Report Type <span class="text-danger">*</span></label>
                    <select name="report_type" class="form-select @error('report_type') is-invalid @enderror" required>
                        <option value="">Select…</option>
                        <option value="quarterly"  {{ old('report_type') === 'quarterly'  ? 'selected' : '' }}>Quarterly</option>
                        <option value="biannual"   {{ old('report_type') === 'biannual'   ? 'selected' : '' }}>Biannual</option>
                    </select>
                    @error('report_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Period Start <span class="text-danger">*</span></label>
                    <input type="date" name="period_start" value="{{ old('period_start') }}"
                           class="form-control @error('period_start') is-invalid @enderror"
                           max="{{ now()->format('Y-m-d') }}" required>
                    @error('period_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium">Period End <span class="text-danger">*</span></label>
                    <input type="date" name="period_end" value="{{ old('period_end') }}"
                           class="form-control @error('period_end') is-invalid @enderror"
                           max="{{ now()->format('Y-m-d') }}" required>
                    @error('period_end')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Student Progress Rating <span class="text-danger">*</span></label>
                <div class="d-flex gap-3">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="student_progress_rating"
                               id="rating{{ $i }}" value="{{ $i }}"
                               {{ old('student_progress_rating') == $i ? 'checked' : '' }} required>
                        <label class="form-check-label" for="rating{{ $i }}">
                            {{ $i }}
                            <small class="text-muted d-block" style="font-size:10px;">
                                @if($i===1) Poor @elseif($i===2) Below Avg @elseif($i===3) Average @elseif($i===4) Good @else Excellent @endif
                            </small>
                        </label>
                    </div>
                    @endfor
                </div>
                @error('student_progress_rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Report Content <span class="text-danger">*</span></label>
                <textarea name="content" rows="8"
                          class="form-control @error('content') is-invalid @enderror"
                          placeholder="Describe the student's progress during this period, activities completed, milestones reached, and overall assessment. (Minimum 100 characters)" required>{{ old('content') }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Challenges / Concerns</label>
                <textarea name="challenges" rows="3"
                          class="form-control @error('challenges') is-invalid @enderror"
                          placeholder="Any challenges the student is facing or concerns you have noticed…">{{ old('challenges') }}</textarea>
                @error('challenges')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Recommendations</label>
                <textarea name="recommendations" rows="3"
                          class="form-control @error('recommendations') is-invalid @enderror"
                          placeholder="Recommendations for the student's next steps…">{{ old('recommendations') }}</textarea>
                @error('recommendations')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>The student will be notified to acknowledge this report.</small>
                <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>Submit Report</button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
@endsection
