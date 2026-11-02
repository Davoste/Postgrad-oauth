@extends('layouts.app')
@section('title', 'Progress Reports')
@section('page-title', 'Progress Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0 small">{{ $thesis->title }}</p>
    </div>
    @if(Auth::user()->hasRole('supervisor'))
    <a href="{{ route('progress-reports.create', $thesis) }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>New Report
    </a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Progress Reports — {{ $reports->count() }} total</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Period</th>
                    <th style="font-size:12px;">Type</th>
                    <th style="font-size:12px;">Supervisor</th>
                    <th style="font-size:12px;">Rating</th>
                    <th style="font-size:12px;">Student Acknowledged</th>
                    <th style="font-size:12px;">Submitted</th>
                    <th style="font-size:12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td style="font-size:13px;">
                        {{ \Carbon\Carbon::parse($report->period_start)->format('d M Y') }}
                        &ndash;
                        {{ \Carbon\Carbon::parse($report->period_end)->format('d M Y') }}
                    </td>
                    <td>
                        <span class="badge bg-{{ $report->report_type === 'quarterly' ? 'info' : 'primary' }}">
                            {{ ucfirst($report->report_type) }}
                        </span>
                    </td>
                    <td style="font-size:13px;">{{ optional($report->supervisor)->user ? $report->supervisor->user->name : '—' }}</td>
                    <td>
                        @php $r = $report->student_progress_rating; @endphp
                        <span class="badge bg-{{ $r >= 4 ? 'success' : ($r >= 3 ? 'warning' : 'danger') }}">
                            {{ $r }}/5
                        </span>
                    </td>
                    <td>
                        @if($report->student_acknowledged_at)
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>{{ $report->student_acknowledged_at->format('d M Y') }}</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </td>
                    <td><small class="text-muted">{{ $report->submitted_at->format('d M Y') }}</small></td>
                    <td>
                        <a href="{{ route('progress-reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-file-alt d-block mb-2 text-muted" style="font-size:2rem;"></i>
                        No progress reports submitted yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
