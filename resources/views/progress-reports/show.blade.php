@extends('layouts.app')
@section('title', 'Progress Report')
@section('page-title', 'Progress Report')

@section('content')
<div class="row justify-content-center">
<div class="col-xl-9">

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('progress-reports.index', $report->thesis) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
    <h6 class="mb-0 fw-semibold">Progress Report Detail</h6>
</div>

<div class="row g-4">

    {{-- Main report --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-semibold">
                        {{ ucfirst($report->report_type) }} Progress Report
                    </h6>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($report->period_start)->format('d M Y') }}
                        &ndash;
                        {{ \Carbon\Carbon::parse($report->period_end)->format('d M Y') }}
                    </small>
                </div>
                @php $r = $report->student_progress_rating; @endphp
                <span class="badge fs-6 px-3 py-2 bg-{{ $r >= 4 ? 'success' : ($r >= 3 ? 'warning' : 'danger') }}">
                    Rating {{ $r }}/5
                </span>
            </div>
            <div class="card-body">
                <h6 class="text-muted small text-uppercase mb-2">Report Content</h6>
                <div class="p-3 bg-light rounded" style="font-size:13px; white-space:pre-wrap; line-height:1.6;">{{ $report->content }}</div>

                @if($report->challenges)
                <h6 class="text-muted small text-uppercase mt-4 mb-2">Challenges / Concerns</h6>
                <div class="p-3 bg-light rounded border-start border-warning border-3" style="font-size:13px; white-space:pre-wrap;">{{ $report->challenges }}</div>
                @endif

                @if($report->recommendations)
                <h6 class="text-muted small text-uppercase mt-4 mb-2">Recommendations</h6>
                <div class="p-3 bg-light rounded border-start border-primary border-3" style="font-size:13px; white-space:pre-wrap;">{{ $report->recommendations }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        {{-- Meta info --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Report Info</h6>
            </div>
            <div class="card-body" style="font-size:13px;">
                <div class="mb-2">
                    <div class="text-muted small">Student</div>
                    <div class="fw-medium">{{ $report->thesis->student->user->name }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Supervisor</div>
                    <div class="fw-medium">{{ optional($report->supervisor)->user ? $report->supervisor->user->name : '—' }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Submitted</div>
                    <div>{{ $report->submitted_at->format('d M Y, H:i') }}</div>
                </div>
                <div>
                    <div class="text-muted small">Student Acknowledgement</div>
                    @if($report->student_acknowledged_at)
                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>{{ $report->student_acknowledged_at->format('d M Y') }}</span>
                    @else
                        <span class="badge bg-secondary">Not yet acknowledged</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Student acknowledge action --}}
        @if(Auth::user()->hasRole('student') && !$report->student_acknowledged_at)
        <div class="card border-0 shadow-sm border-warning">
            <div class="card-body text-center py-4">
                <i class="fas fa-signature text-warning d-block mb-2" style="font-size:2rem;"></i>
                <p class="mb-3 small">Please review and acknowledge this progress report.</p>
                <form method="POST" action="{{ route('progress-reports.acknowledge', $report) }}">
                    @csrf
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-check me-2"></i>Acknowledge Report
                    </button>
                </form>
            </div>
        </div>
        @elseif(Auth::user()->hasRole('student') && $report->student_acknowledged_at)
        <div class="card border-0 shadow-sm border-success">
            <div class="card-body text-center py-3">
                <i class="fas fa-check-circle text-success d-block mb-1" style="font-size:1.5rem;"></i>
                <small class="text-muted">You acknowledged this report on<br>{{ $report->student_acknowledged_at->format('d M Y') }}.</small>
            </div>
        </div>
        @endif

    </div>
</div>

</div>
</div>
@endsection
