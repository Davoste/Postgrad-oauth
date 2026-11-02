@extends('layouts.app')
@section('title', 'Progress Reports')
@section('page-title', 'Progress Reports')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">My Students</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Student</th>
                    <th style="font-size:12px;">Reports</th>
                    <th style="font-size:12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($theses as $thesis)
                <tr>
                    <td>
                        <div class="fw-medium" style="font-size:13px;">{{ $thesis->student->user->name }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ $thesis->student->reg_no }}</div>
                    </td>
                    <td><span class="badge bg-secondary">{{ $thesis->progress_reports_count }}</span></td>
                    <td>
                        <a href="{{ route('progress-reports.create', $thesis) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-plus me-1"></i>New Report
                        </a>
                        <a href="{{ route('progress-reports.index', $thesis) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">No students assigned yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Submitted Reports — {{ $reports->total() }} total</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Student</th>
                    <th style="font-size:12px;">Period</th>
                    <th style="font-size:12px;">Type</th>
                    <th style="font-size:12px;">Rating</th>
                    <th style="font-size:12px;">Acknowledged</th>
                    <th style="font-size:12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td>
                        <div class="fw-medium" style="font-size:13px;">{{ $report->thesis->student->user->name }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ $report->thesis->student->reg_no }}</div>
                    </td>
                    <td style="font-size:13px;">
                        {{ $report->period_start->format('d M Y') }} &ndash; {{ $report->period_end->format('d M Y') }}
                    </td>
                    <td><span class="badge bg-{{ $report->report_type === 'quarterly' ? 'info' : 'primary' }}">{{ ucfirst($report->report_type) }}</span></td>
                    <td>
                        <span class="badge bg-{{ $report->student_progress_rating >= 4 ? 'success' : ($report->student_progress_rating >= 3 ? 'warning' : 'danger') }}">
                            {{ $report->student_progress_rating }}/5
                        </span>
                    </td>
                    <td>
                        @if($report->student_acknowledged_at)
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>{{ $report->student_acknowledged_at->format('d M Y') }}</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('progress-reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">No progress reports submitted yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reports->hasPages())
    <div class="card-footer bg-white border-top">{{ $reports->links() }}</div>
    @endif
</div>
@endsection