@extends('layouts.app')
@section('title', 'Ethics Review')
@section('page-title', 'Ethics Review')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-10">

{{-- Back + header --}}
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('ethics.pending') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
    <h6 class="mb-0 fw-semibold">Ethics Application Review</h6>
</div>

<div class="row g-4">

    {{-- Left: submission details --}}
    <div class="col-lg-6">

        {{-- Student & thesis info --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-user-graduate me-2 text-primary"></i>Student &amp; Thesis</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0" style="font-size:13px;">
                    <dt class="col-sm-4 text-muted">Student</dt>
                    <dd class="col-sm-8 fw-medium">{{ $submission->thesis->student->user->name }}</dd>

                    <dt class="col-sm-4 text-muted">Reg. No.</dt>
                    <dd class="col-sm-8">{{ $submission->thesis->student->reg_no ?? $submission->thesis->student->student_id ?? '—' }}</dd>

                    <dt class="col-sm-4 text-muted">Supervisor</dt>
                    <dd class="col-sm-8">{{ optional($submission->thesis->supervisor)->user ? $submission->thesis->supervisor->user->name : 'Not assigned' }}</dd>

                    <dt class="col-sm-4 text-muted">Thesis Title</dt>
                    <dd class="col-sm-8">{{ $submission->thesis->title }}</dd>

                    <dt class="col-sm-4 text-muted">Submitted</dt>
                    <dd class="col-sm-8">{{ $submission->submitted_at->format('d M Y, H:i') }}</dd>

                    <dt class="col-sm-4 text-muted">Status</dt>
                    <dd class="col-sm-8">
                        <span class="badge bg-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }} text-{{ in_array($submission->status, ['submitted','under_review']) ? 'dark' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                        </span>
                    </dd>
                </dl>
            </div>
        </div>

        {{-- Application description --}}
        @if($submission->description)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-file-alt me-2 text-secondary"></i>Application Description</h6>
            </div>
            <div class="card-body">
                <p class="mb-0" style="font-size:13px; white-space:pre-wrap;">{{ $submission->description }}</p>
            </div>
        </div>
        @endif

        {{-- Submitted documents --}}
        @if($submission->documents)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-paperclip me-2 text-secondary"></i>Attached Documents</h6>
            </div>
            <div class="card-body p-0">
                @foreach($submission->documents as $i => $doc)
                <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom">
                    <i class="fas fa-file-pdf text-danger"></i>
                    <span style="font-size:13px;" class="flex-grow-1">{{ $doc['original_name'] }}</span>
                    <a href="{{ route('documents.download', [$submission, $i]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Previous reviews --}}
        @if($submission->reviews->count())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-history me-2 text-secondary"></i>Previous Reviews</h6>
            </div>
            <div class="card-body p-0">
                @foreach($submission->reviews as $review)
                <div class="px-3 py-2 border-bottom" style="font-size:13px;">
                    <div class="d-flex justify-content-between">
                        <span class="fw-medium">{{ $review->reviewer->name }}</span>
                        <span class="badge bg-{{ $review->decision === 'approved' ? 'success' : ($review->decision === 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst(str_replace('_', ' ', $review->decision)) }}
                        </span>
                    </div>
                    <div class="text-muted small mt-1">{{ $review->recommendations }}</div>
                    <div class="text-muted small">{{ optional($review->reviewed_at)->format('d M Y') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Right: review form or decision summary --}}
    <div class="col-lg-6">
        @if(in_array($submission->status, ['submitted', 'under_review']))
        {{-- Pending: show review form --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-clipboard-check me-2 text-success"></i>Submit Review Decision</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('ethics.review', $submission) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Decision <span class="text-danger">*</span></label>
                        <select name="decision" class="form-select @error('decision') is-invalid @enderror" required>
                            <option value="">Select decision…</option>
                            <option value="approved"          {{ old('decision') === 'approved'          ? 'selected' : '' }}>Approve</option>
                            <option value="rejected"          {{ old('decision') === 'rejected'          ? 'selected' : '' }}>Reject</option>
                            <option value="revision_required" {{ old('decision') === 'revision_required' ? 'selected' : '' }}>Revision Required</option>
                        </select>
                        @error('decision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Comments <span class="text-danger">*</span></label>
                        <textarea name="comments" rows="5"
                                  class="form-control @error('comments') is-invalid @enderror"
                                  placeholder="Provide detailed feedback on the ethics application…" required>{{ old('comments') }}</textarea>
                        @error('comments')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Recommendations <span class="text-danger">*</span></label>
                        <textarea name="recommendations" rows="4"
                                  class="form-control @error('recommendations') is-invalid @enderror"
                                  placeholder="List specific recommendations or conditions for approval…" required>{{ old('recommendations') }}</textarea>
                        @error('recommendations')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="alert alert-info py-2 small">
                        <i class="fas fa-info-circle me-1"></i>
                        If approved, the thesis will automatically advance to the Data Collection stage. The student will be notified by email.
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('ethics.pending') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-2"></i>Submit Decision
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        {{-- Already reviewed: show decision summary --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-clipboard-check me-2 text-secondary"></i>Review Decision</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    @if($submission->status === 'approved')
                        <span class="badge bg-success fs-6">Approved</span>
                    @elseif($submission->status === 'rejected')
                        <span class="badge bg-danger fs-6">Rejected</span>
                    @elseif($submission->status === 'revision_required')
                        <span class="badge bg-warning text-dark fs-6">Revision Required</span>
                    @endif
                    <small class="text-muted">Reviewed {{ optional($submission->reviewed_at)->format('d M Y, H:i') }}</small>
                </div>

                @foreach($submission->feedbacks as $fb)
                <div class="mb-3">
                    <p class="small fw-medium text-muted mb-1">Comments by {{ $fb->reviewer->name }}</p>
                    <p class="mb-0" style="font-size:13px; white-space:pre-wrap;">{{ $fb->comments }}</p>
                </div>
                @endforeach

                @foreach($submission->reviews as $rv)
                @if($rv->recommendations)
                <div class="border-top pt-3">
                    <p class="small fw-medium text-muted mb-1">Recommendations</p>
                    <p class="mb-0" style="font-size:13px; white-space:pre-wrap;">{{ $rv->recommendations }}</p>
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <a href="{{ route('ethics.pending') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Queue
        </a>
        @endif
    </div>

</div>
</div>
</div>
@endsection
