@extends('layouts.app')
@section('title', 'My Thesis')
@section('page-title', 'My Thesis')

@section('content')
@php
$stageDescriptions = [
    'concept_development'  => 'Develop and submit your thesis concept for approval.',
    'proposal_development' => 'Create a detailed research proposal.',
    'ethical_approval'     => 'Obtain ethical clearance for your research.',
    'data_collection'      => 'Collect and analyse research data.',
    'writing_thesis'       => 'Write and finalise the thesis document.',
    'intent_to_submit'     => 'Declare intent to submit before the final deadline.',
    'submitted'            => 'Thesis submitted for examination.',
    'examination'          => 'Thesis is under examination.',
    'graduated'            => 'Congratulations — thesis completed!',
];
$stageColors = [
    'concept_development'=>'secondary','proposal_development'=>'info',
    'ethical_approval'=>'warning','data_collection'=>'primary',
    'writing_thesis'=>'info','intent_to_submit'=>'warning',
    'submitted'=>'primary','examination'=>'warning','graduated'=>'success',
];
@endphp

{{-- Header --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h5 class="fw-semibold mb-1">{{ $thesis->title }}</h5>
                <p class="text-muted mb-0 small">{{ $stageDescriptions[$thesis->current_stage] ?? '' }}</p>
            </div>
            <div class="text-end">
                <span class="badge fs-6 px-3 py-2 bg-{{ $stageColors[$thesis->current_stage] ?? 'secondary' }}">
                    {{ $thesis->stage_label }}
                </span>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <div class="progress" style="width:130px;height:6px;">
                        <div class="progress-bar bg-success" style="width:{{ $thesis->progress_percentage }}%;"></div>
                    </div>
                    <small class="text-muted">{{ number_format($thesis->progress_percentage, 0) }}%</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- LEFT: Full stage tracker --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Thesis Progress Tracker</h6>
            </div>
            <div class="card-body" style="padding-left:44px;position:relative;">
                {{-- vertical line --}}
                <div style="position:absolute;left:28px;top:24px;bottom:24px;width:2px;background:#e9ecef;"></div>

                @foreach($stageProgress as $stage => $progress)
                @php
                    $sub      = $progress['submission'];
                    $feedback = $progress['feedback'];
                    $status   = $progress['status']; // completed | current | pending

                    // determine dot colour
                    if ($status === 'completed') {
                        $dotBg = '#198754';
                    } elseif ($status === 'current') {
                        if ($sub && $feedback && in_array($feedback->decision, ['rejected','revision_required'])) {
                            $dotBg = '#fd7e14'; // orange = action needed
                        } elseif ($sub) {
                            $dotBg = '#0d6efd'; // blue = under review
                        } else {
                            $dotBg = '#0d6efd';
                        }
                    } else {
                        $dotBg = '#dee2e6';
                    }

                    // determine card style
                    if ($status === 'completed') {
                        $cardBg    = '#f8fff9';
                        $cardBorder = '#198754';
                    } elseif ($status === 'current') {
                        $cardBg    = '#f0f8ff';
                        $cardBorder = '#0d6efd';
                    } else {
                        $cardBg    = '#f8f9fa';
                        $cardBorder = '#dee2e6';
                    }
                @endphp

                <div class="d-flex align-items-start gap-3 mb-4" style="position:relative;">
                    {{-- dot --}}
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:28px;height:28px;margin-left:-14px;z-index:1;background:{{ $dotBg }};color:white;font-size:11px;">
                        @if($status === 'completed')
                            <i class="fas fa-check"></i>
                        @elseif($status === 'current' && $sub && $feedback && in_array($feedback->decision, ['rejected','revision_required']))
                            <i class="fas fa-exclamation" style="font-size:10px;"></i>
                        @elseif($status === 'current')
                            <i class="fas fa-circle" style="font-size:8px;"></i>
                        @else
                            <i class="far fa-circle" style="font-size:8px;color:#adb5bd;"></i>
                        @endif
                    </div>

                    {{-- card --}}
                    <div class="border rounded p-3 flex-grow-1"
                         style="background:{{ $cardBg }};border-color:{{ $cardBorder }} !important;">

                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-1 mb-1">
                            <div class="fw-semibold" style="font-size:13px;">{{ $progress['name'] }}</div>
                            {{-- status badge --}}
                            @if($status === 'completed')
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>Approved</span>
                            @elseif($status === 'current')
                                @if(!$sub)
                                    <span class="badge bg-primary">Action Required</span>
                                @elseif($feedback && $feedback->decision === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($feedback && $feedback->decision === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($feedback && $feedback->decision === 'revision_required')
                                    <span class="badge bg-warning text-dark">Revision Required</span>
                                @else
                                    <span class="badge bg-secondary">Under Review</span>
                                @endif
                            @else
                                <span class="badge bg-light text-muted border">Upcoming</span>
                            @endif
                        </div>

                        <div class="text-muted mb-2" style="font-size:11px;">{{ $stageDescriptions[$stage] ?? '' }}</div>

                        {{-- submission info --}}
                        @if($sub)
                        <div class="d-flex flex-wrap gap-2 align-items-center mb-2" style="font-size:11px;">
                            <span class="text-muted"><i class="fas fa-upload me-1"></i>Submitted {{ $sub->submitted_at->format('d M Y') }}</span>
                            @if($sub->documents && count($sub->documents))
                                <span class="text-muted"><i class="fas fa-paperclip me-1"></i>{{ count($sub->documents) }} file(s)</span>
                            @endif
                        </div>
                        @endif

                        {{-- feedback --}}
                        @if($feedback)
                        <div class="p-2 rounded mb-2" style="background:rgba(0,0,0,.04);font-size:12px;">
                            <span class="fw-medium">Feedback:</span> {{ Str::limit($feedback->comments, 120) }}
                        </div>
                        @endif

                        {{-- Action buttons for current stage --}}
                        @if($status === 'current')
                            @if($stage === 'concept_development')
                                <button class="btn btn-sm btn-primary mt-1" data-bs-toggle="modal" data-bs-target="#submitConceptModal">
                                    <i class="fas fa-lightbulb me-1"></i>{{ $sub ? 'Resubmit Concept' : 'Submit Concept' }}
                                </button>
                            @elseif($stage === 'proposal_development')
                                <button class="btn btn-sm btn-success mt-1" data-bs-toggle="modal" data-bs-target="#submitProposalModal">
                                    <i class="fas fa-file-alt me-1"></i>{{ $sub ? 'Resubmit Proposal' : 'Submit Proposal' }}
                                </button>
                            @elseif($stage === 'ethical_approval')
                                <button class="btn btn-sm btn-warning mt-1" data-bs-toggle="modal" data-bs-target="#submitEthicsModal">
                                    <i class="fas fa-shield-alt me-1"></i>{{ $sub ? 'Resubmit Ethics Form' : 'Submit Ethics Form' }}
                                </button>
                            @elseif($stage === 'writing_thesis')
                                @if(!$thesis->intent_to_submit_date)
                                <button class="btn btn-sm btn-warning mt-1 me-1" data-bs-toggle="modal" data-bs-target="#submitIntentModal">
                                    <i class="fas fa-calendar-check me-1"></i>Declare Intent
                                </button>
                                @else
                                <span class="badge bg-success mt-1 me-1"><i class="fas fa-calendar-check me-1"></i>Intent: {{ $thesis->intent_to_submit_date->format('d M Y') }}</span>
                                @endif
                                <button class="btn btn-sm btn-info mt-1" data-bs-toggle="modal" data-bs-target="#submitThesisModal">
                                    <i class="fas fa-book me-1"></i>{{ $sub ? 'Resubmit Thesis' : 'Upload Thesis' }}
                                </button>
                            @elseif($stage === 'intent_to_submit')
                                <button class="btn btn-sm btn-info mt-1" data-bs-toggle="modal" data-bs-target="#submitThesisModal">
                                    <i class="fas fa-book me-1"></i>{{ $sub ? 'Resubmit Thesis' : 'Upload Final Thesis' }}
                                </button>
                            @elseif($stage === 'data_collection')
                                <span class="text-muted" style="font-size:11px;"><i class="fas fa-info-circle me-1"></i>Await supervisor progress reports and meeting sign-offs.</span>
                            @elseif(in_array($stage, ['submitted','examination']))
                                <span class="text-muted" style="font-size:11px;"><i class="fas fa-clock me-1"></i>Awaiting examiner decision.</span>
                            @endif
                        @endif

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RIGHT: sidebar --}}
    <div class="col-lg-5">

        {{-- Current status card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Current Status</h6>
            </div>
            <div class="card-body" style="font-size:13px;">
                @if($currentSubmission)
                <div class="mb-3">
                    <div class="text-muted small">Latest Submission</div>
                    <div class="fw-medium">{{ $currentSubmission->title }}</div>
                    <span class="badge bg-{{ $currentSubmission->status === 'approved' ? 'success' : ($currentSubmission->status === 'rejected' ? 'danger' : ($currentSubmission->status === 'revision_required' ? 'warning' : 'secondary')) }}">
                        {{ ucfirst(str_replace('_', ' ', $currentSubmission->status)) }}
                    </span>
                </div>
                @endif

                @if($latestFeedback)
                <div class="mb-3">
                    <div class="text-muted small">Latest Feedback</div>
                    <div>{{ Str::limit($latestFeedback->comments, 120) }}</div>
                    <small class="text-primary">By: {{ $latestFeedback->reviewer->name }}</small>
                </div>
                @endif

                @if($thesis->supervisor)
                <div class="mb-2">
                    <div class="text-muted small">Supervisor</div>
                    <div class="fw-medium">{{ $thesis->supervisor->user->name }}</div>
                    <div class="text-muted small">{{ $thesis->supervisor->department }}</div>
                </div>
                @if($thesis->supervisor_id)
                <button class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="modal" data-bs-target="#requestMeetingModal">
                    <i class="fas fa-calendar-plus me-1"></i>Request Meeting
                </button>
                @endif
                @else
                <div class="alert alert-warning py-2 small mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>No supervisor assigned yet.
                </div>
                @endif
            </div>
        </div>

        {{-- Quick links --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Quick Links</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('thesis.submissions') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list me-2"></i>All Submissions
                </a>
                <a href="{{ route('thesis.feedback') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-comments me-2"></i>All Feedback
                </a>
                <a href="{{ route('thesis.milestones') }}" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-tasks me-2"></i>Milestones
                </a>
                <a href="{{ route('supervision.meetings') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-calendar-alt me-2"></i>Supervision Meetings
                </a>
            </div>
        </div>

        {{-- Overdue Milestones --}}
        @if($overdueMilestones->count() > 0)
        <div class="card border-0 shadow-sm mb-4" style="border-color:#dc3545 !important;">
            <div class="card-header bg-danger text-white py-3">
                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Overdue Milestones</h6>
            </div>
            <div class="card-body p-0">
                @foreach($overdueMilestones as $m)
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-flag text-danger mt-1"></i>
                    <div>
                        <div class="fw-medium" style="font-size:13px;">{{ $m->milestone_name }}</div>
                        <small class="text-danger">Due: {{ $m->target_date->format('d M Y') }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Recent Meetings --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Recent Meetings</h6>
                <a href="{{ route('supervision.meetings') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentMeetings as $meeting)
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-calendar mt-1 text-primary"></i>
                    <div class="flex-grow-1">
                        <div style="font-size:13px;" class="text-truncate" title="{{ $meeting->agenda }}">{{ $meeting->agenda }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</small>
                        @if(!$meeting->signed_student)
                        <div>
                            <form method="POST" action="{{ route('meetings.sign', $meeting) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-warning" style="font-size:11px;padding:2px 8px;">
                                    Sign Off
                                </button>
                            </form>
                        </div>
                        @else
                        <small class="text-success d-block"><i class="fas fa-check me-1"></i>Signed</small>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">No meetings yet.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>

{{-- Modals --}}
@include('thesis.modals.submit-concept')
@include('thesis.modals.submit-proposal')
@include('thesis.modals.submit-ethics')
@include('thesis.modals.submit-intent')
@include('thesis.modals.submit-thesis')
@include('thesis.modals.request-meeting')
@endsection
