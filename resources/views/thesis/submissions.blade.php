@extends('layouts.app')

@section('title', 'My Submissions')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">My Submissions</h2>
                            <p class="text-muted mb-0">Track all your thesis submissions and their status</p>
                        </div>
                        <a href="{{ route('thesis.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions by Stage -->
    @if($submissions->count() > 0)
        @foreach($submissions as $stage => $stageSubmissions)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">{{ ucfirst(str_replace('_', ' ', $stage)) }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Submitted</th>
                                        <th>Status</th>
                                        <th>Reviewed</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stageSubmissions as $submission)
                                    <tr>
                                        <td>
                                            <strong>{{ Str::limit($submission->title, 50) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $submission->submission_type)) }}</span>
                                        </td>
                                        <td>{{ $submission->submitted_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($submission->reviewed_at)
                                                {{ $submission->reviewed_at->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                        data-bs-target="#detailsModal{{ $submission->id }}">
                                                    <i class="fas fa-eye"></i> Details
                                                </button>
                                                @if($submission->documents)
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download"></i> Files
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @foreach($submission->documents as $i => $doc)
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('documents.download', [$submission, $i]) }}">
                                                                <i class="fas fa-file me-1"></i>{{ $doc['original_name'] }}
                                                            </a>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-file-alt text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No Submissions Yet</h5>
                    <p class="text-muted">You haven't made any thesis submissions yet.</p>
                    <a href="{{ route('thesis.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Make Your First Submission
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Details Modals -->
@foreach($submissions->flatten() as $submission)
<div class="modal fade" id="detailsModal{{ $submission->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $submission->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel{{ $submission->id }}">Submission Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Submission Information</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Title:</strong></td>
                                <td>{{ $submission->title }}</td>
                            </tr>
                            <tr>
                                <td><strong>Stage:</strong></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $submission->stage)) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td>{{ ucfirst(str_replace('_', ' ', $submission->submission_type)) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $submission->status === 'approved' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Submitted:</strong></td>
                                <td>{{ $submission->submitted_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @if($submission->reviewed_at)
                            <tr>
                                <td><strong>Reviewed:</strong></td>
                                <td>{{ $submission->reviewed_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Description</h6>
                        <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                            {{ $submission->description ?? 'No description provided.' }}
                        </div>

                        @if($submission->documents)
                        <h6 class="mt-3">Documents</h6>
                        <ul class="list-group">
                            @foreach($submission->documents as $i => $doc)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-file me-1 text-muted"></i>{{ $doc['original_name'] }}
                                    <small class="text-muted ms-2">{{ number_format($doc['size'] / 1024, 1) }} KB</small>
                                </div>
                                <a href="{{ route('documents.download', [$submission, $i]) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>

                <!-- Feedback Section -->
                @if($submission->feedbacks->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Feedback & Reviews</h6>
                        @foreach($submission->feedbacks as $feedback)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>{{ $feedback->reviewer->name }}</strong>
                                        <small class="text-muted ms-2">{{ $feedback->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $feedback->decision === 'approved' ? 'success' : ($feedback->decision === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($feedback->decision) }}
                                    </span>
                                </div>
                                <p class="mb-2">{{ $feedback->comments }}</p>
                                @if($feedback->action_items)
                                <div class="alert alert-info">
                                    <strong>Action Items:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach(json_decode($feedback->action_items) as $item)
                                        <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Reviews Section -->
                @if($submission->reviews->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <h6>Formal Reviews</h6>
                        @foreach($submission->reviews as $review)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>{{ $review->reviewer->name }}</strong>
                                        <small class="text-muted ms-2">{{ $review->reviewed_at->format('M d, Y') }}</small>
                                        <span class="badge bg-secondary ms-2">{{ ucfirst(str_replace('_', ' ', $review->review_type)) }}</span>
                                    </div>
                                    @if($review->rating)
                                    <div class="text-end">
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <small class="text-muted">Rating: {{ $review->rating }}/5</small>
                                    </div>
                                    @endif
                                </div>

                                @if($review->strengths)
                                <div class="mb-2">
                                    <strong>Strengths:</strong>
                                    <p class="mb-1">{{ $review->strengths }}</p>
                                </div>
                                @endif

                                @if($review->weaknesses)
                                <div class="mb-2">
                                    <strong>Areas for Improvement:</strong>
                                    <p class="mb-1">{{ $review->weaknesses }}</p>
                                </div>
                                @endif

                                <div class="mb-2">
                                    <strong>Recommendations:</strong>
                                    <p class="mb-1">{{ $review->recommendations }}</p>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-{{ $review->decision === 'approved' ? 'success' : ($review->decision === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $review->decision)) }}
                                    </span>
                                    @if($review->requires_meeting)
                                    <small class="text-info"><i class="fas fa-calendar me-1"></i>Meeting Required</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection