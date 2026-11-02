@extends('layouts.app')

@section('title', 'My Feedback')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">My Feedback</h2>
                            <p class="text-muted mb-0">View all feedback and reviews on your thesis work</p>
                        </div>
                        <a href="{{ route('thesis.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback by Stage -->
    @if($feedbacks->count() > 0)
        @foreach($feedbacks as $stage => $stageFeedbacks)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">{{ ucfirst(str_replace('_', ' ', $stage)) }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($stageFeedbacks as $feedback)
                        <div class="card mb-3 {{ $feedback->is_final ? 'border-primary' : '' }}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1">
                                                    Feedback from {{ $feedback->reviewer->name }}
                                                    @if($feedback->is_final)
                                                        <span class="badge bg-primary ms-2">Final Review</span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">{{ $feedback->created_at->format('M d, Y H:i') }}</small>
                                            </div>
                                            <span class="badge bg-{{ $feedback->decision === 'approved' ? 'success' : ($feedback->decision === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($feedback->decision) }}
                                            </span>
                                        </div>

                                        <div class="mb-3">
                                            <strong>Comments:</strong>
                                            <p class="mt-2">{{ $feedback->comments }}</p>
                                        </div>

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

                                        @if($feedback->deadline)
                                        <div class="alert alert-warning">
                                            <strong>Deadline:</strong> {{ $feedback->deadline->format('M d, Y') }}
                                            @if($feedback->deadline->isPast())
                                                <span class="text-danger ms-2">(Overdue)</span>
                                            @else
                                                <span class="text-success ms-2">({{ $feedback->deadline->diffForHumans() }})</span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        @if($feedback->submission)
                                        <div class="border-start ps-3">
                                            <h6>Related Submission</h6>
                                            <p class="mb-1"><strong>{{ $feedback->submission->title }}</strong></p>
                                            <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $feedback->submission->submission_type)) }}</small><br>
                                            <small class="text-muted">Submitted: {{ $feedback->submission->submitted_at->format('M d, Y') }}</small>

                                            @if($feedback->submission->documents)
                                            <div class="mt-2">
                                                <strong>Documents:</strong>
                                                <ul class="list-unstyled mt-1">
                                                    @foreach($feedback->submission->documents as $i => $doc)
                                                    <li class="small mb-1">
                                                        <a href="{{ route('documents.download', [$feedback->submission, $i]) }}" class="text-decoration-none">
                                                            <i class="fas fa-file-download me-1"></i>{{ $doc['original_name'] }}
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
                    <i class="fas fa-comments text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No Feedback Yet</h5>
                    <p class="text-muted">You haven't received any feedback on your submissions yet.</p>
                    <a href="{{ route('thesis.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Make a Submission
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection