@extends('layouts.app')
@section('title', 'Ethics Reviews')
@section('page-title', 'Ethics Review Queue')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage ethics applications submitted for committee review</p>
    <div class="d-flex gap-2">
        <span class="badge bg-warning text-dark fs-6">{{ $pending->count() }} pending</span>
        <span class="badge bg-secondary fs-6">{{ $reviewed->count() }} reviewed</span>
    </div>
</div>

<ul class="nav nav-tabs mb-0" id="ethicsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-pane" type="button" role="tab">
            Pending
            @if($pending->count())
            <span class="badge bg-warning text-dark ms-1">{{ $pending->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="reviewed-tab" data-bs-toggle="tab" data-bs-target="#reviewed-pane" type="button" role="tab">
            Reviewed
            @if($reviewed->count())
            <span class="badge bg-secondary ms-1">{{ $reviewed->count() }}</span>
            @endif
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- Pending tab --}}
    <div class="tab-pane fade show active" id="pending-pane" role="tabpanel">
        <div class="card border-0 shadow-sm border-top-0" style="border-radius:0 0 .5rem .5rem;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Student</th>
                            <th style="font-size:12px;">Thesis Title</th>
                            <th style="font-size:12px;">Supervisor</th>
                            <th style="font-size:12px;">Submitted</th>
                            <th style="font-size:12px;">Status</th>
                            <th style="font-size:12px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending as $submission)
                        <tr>
                            <td>
                                <div class="fw-medium" style="font-size:13px;">{{ $submission->thesis->student->user->name }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $submission->thesis->student->reg_no ?? $submission->thesis->student->student_id ?? '' }}</div>
                            </td>
                            <td>
                                <div style="font-size:13px; max-width:250px;" class="text-truncate" title="{{ $submission->thesis->title }}">
                                    {{ $submission->thesis->title }}
                                </div>
                            </td>
                            <td style="font-size:13px;">{{ optional($submission->thesis->supervisor)->user ? $submission->thesis->supervisor->user->name : '—' }}</td>
                            <td><small class="text-muted">{{ $submission->submitted_at->format('d M Y') }}</small></td>
                            <td>
                                @if($submission->status === 'submitted')
                                    <span class="badge bg-warning text-dark">Submitted</span>
                                @elseif($submission->status === 'under_review')
                                    <span class="badge bg-info">Under Review</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $submission->status)) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('ethics.show', $submission) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search me-1"></i>Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle text-success d-block mb-2" style="font-size:2rem;"></i>
                                No pending ethics submissions.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Reviewed tab --}}
    <div class="tab-pane fade" id="reviewed-pane" role="tabpanel">
        <div class="card border-0 shadow-sm border-top-0" style="border-radius:0 0 .5rem .5rem;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Student</th>
                            <th style="font-size:12px;">Thesis Title</th>
                            <th style="font-size:12px;">Supervisor</th>
                            <th style="font-size:12px;">Reviewed</th>
                            <th style="font-size:12px;">Decision</th>
                            <th style="font-size:12px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviewed as $submission)
                        <tr>
                            <td>
                                <div class="fw-medium" style="font-size:13px;">{{ $submission->thesis->student->user->name }}</div>
                                <div class="text-muted" style="font-size:11px;">{{ $submission->thesis->student->reg_no ?? $submission->thesis->student->student_id ?? '' }}</div>
                            </td>
                            <td>
                                <div style="font-size:13px; max-width:250px;" class="text-truncate" title="{{ $submission->thesis->title }}">
                                    {{ $submission->thesis->title }}
                                </div>
                            </td>
                            <td style="font-size:13px;">{{ optional($submission->thesis->supervisor)->user ? $submission->thesis->supervisor->user->name : '—' }}</td>
                            <td><small class="text-muted">{{ optional($submission->reviewed_at)->format('d M Y') ?? '—' }}</small></td>
                            <td>
                                @if($submission->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($submission->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($submission->status === 'revision_required')
                                    <span class="badge bg-warning text-dark">Revision Required</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $submission->status)) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('ethics.show', $submission) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-inbox d-block mb-2 text-muted" style="font-size:2rem;"></i>
                                No reviewed submissions yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
