@extends('layouts.app')
@section('title', 'Thesis Detail')
@section('page-title', 'Thesis Detail')

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('admin.theses') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
    <h5 class="mb-0 fw-semibold">{{ $thesis->title }}</h5>
</div>

<div class="row g-4">
    {{-- Left column: thesis info + stage progress --}}
    <div class="col-lg-8">

        {{-- Info card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Overview</h6>
                @php
                $stageColors=['concept_development'=>'secondary','proposal_development'=>'info','ethical_approval'=>'warning','data_collection'=>'primary','writing_thesis'=>'info','intent_to_submit'=>'warning','submitted'=>'primary','examination'=>'warning','graduated'=>'success'];
                @endphp
                <span class="badge bg-{{ $stageColors[$thesis->current_stage] ?? 'secondary' }}">{{ $thesis->stage_label }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Student</div>
                        <div class="fw-medium">{{ $thesis->student->user->name }}</div>
                        <div class="text-muted small">{{ $thesis->student->reg_no }} &bull; {{ $thesis->student->program }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Supervisor</div>
                        @if($thesis->supervisor)
                            <div class="fw-medium">{{ $thesis->supervisor->user->name }}</div>
                            <div class="text-muted small">{{ $thesis->supervisor->department }}</div>
                        @else
                            <span class="badge bg-warning text-dark">Unassigned</span>
                            <div class="mt-1">
                                <a href="{{ route('admin.thesis.allocate-supervisor.form', $thesis) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-user-plus me-1"></i>Assign Supervisor
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Progress</div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <div class="progress flex-grow-1" style="height:8px;">
                                <div class="progress-bar bg-success" style="width:{{ $thesis->progress_percentage }}%;"></div>
                            </div>
                            <small class="fw-medium">{{ number_format($thesis->progress_percentage, 0) }}%</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Started</div>
                        <div class="fw-medium">{{ $thesis->created_at->format('d M Y') }}</div>
                    </div>
                </div>

                {{-- Transition form (admin manual override) --}}
                @if(Auth::user()->hasRole('admin'))
                <hr>
                <form method="POST" action="{{ route('thesis.transition', $thesis) }}" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-md-5">
                        <label class="form-label small fw-medium">Move to Stage</label>
                        <select name="transition" class="form-select form-select-sm" required>
                            <option value="">Select stage…</option>
                            @foreach(\App\Models\Thesis::STAGE_LABELS as $key => $label)
                                @if($key !== $thesis->current_stage)
                                <option value="{{ $key }}">{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-medium">Notes</label>
                        <input type="text" name="notes" class="form-control form-control-sm" placeholder="Optional note">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Move</button>
                    </div>
                </form>
                @endif
            </div>
        </div>

        {{-- Stage Progress --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Stage Progress</h6>
            </div>
            <div class="card-body">
                @foreach($stageProgress as $stage => $progress)
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="flex-shrink-0 mt-1">
                        @if($progress['status'] === 'completed')
                            <i class="fas fa-check-circle text-success"></i>
                        @elseif($progress['status'] === 'current')
                            <i class="fas fa-dot-circle text-primary"></i>
                        @else
                            <i class="far fa-circle text-muted"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-medium" style="font-size:13px;">{{ $progress['name'] }}</div>
                        @if($progress['submission'])
                            <small class="text-success">Submitted: {{ $progress['submission']->submitted_at->format('d M Y') }}</small>
                        @endif
                        @if($progress['feedback'])
                            <small class="ms-2 text-{{ $progress['feedback']->decision === 'approved' ? 'success' : ($progress['feedback']->decision === 'rejected' ? 'danger' : 'warning') }}">
                                &bull; {{ ucfirst($progress['feedback']->decision) }}
                            </small>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Progress Reports --}}
        @if($thesis->progressReports->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">Progress Reports</h6>
                <a href="{{ route('progress-reports.index', $thesis) }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Type</th>
                            <th style="font-size:12px;">Period</th>
                            <th style="font-size:12px;">Supervisor</th>
                            <th style="font-size:12px;">Rating</th>
                            <th style="font-size:12px;">Acknowledged</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($thesis->progressReports->take(5) as $report)
                        <tr>
                            <td><span class="badge bg-{{ $report->report_type === 'quarterly' ? 'info' : 'primary' }}">{{ ucfirst($report->report_type) }}</span></td>
                            <td><small>{{ \Carbon\Carbon::parse($report->period_start)->format('M Y') }} – {{ \Carbon\Carbon::parse($report->period_end)->format('M Y') }}</small></td>
                            <td style="font-size:13px;">{{ $report->supervisor->user->name }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $report->student_progress_rating ? '' : '-o' }} text-{{ $i <= $report->student_progress_rating ? 'warning' : 'muted' }}" style="font-size:11px;"></i>
                                @endfor
                            </td>
                            <td>
                                @if($report->isAcknowledged())
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Right column: meetings + milestones --}}
    <div class="col-lg-4">

        {{-- Recent Meetings --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Recent Meetings</h6>
            </div>
            <div class="card-body p-0">
                @forelse($thesis->meetings->take(5) as $meeting)
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-calendar-check mt-1 {{ ($meeting->signed_student && $meeting->signed_supervisor) ? 'text-success' : 'text-warning' }}"></i>
                    <div>
                        <div style="font-size:13px;" class="text-truncate" style="max-width:200px;" title="{{ $meeting->agenda }}">{{ $meeting->agenda }}</div>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</small>
                        @if(!($meeting->signed_student && $meeting->signed_supervisor))
                            <small class="text-warning d-block">Pending sign-off</small>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">No meetings yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Milestones --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Milestones</h6>
            </div>
            <div class="card-body p-0">
                @forelse($thesis->milestones->take(6) as $milestone)
                <div class="d-flex align-items-start gap-2 p-3 border-bottom">
                    <i class="fas fa-flag mt-1 text-{{ $milestone->status === 'completed' ? 'success' : (optional($milestone->target_date)->isPast() ? 'danger' : 'warning') }}"></i>
                    <div>
                        <div style="font-size:13px;">{{ $milestone->milestone_name }}</div>
                        <small class="text-{{ optional($milestone->target_date)->isPast() && $milestone->status !== 'completed' ? 'danger' : 'muted' }}">
                            Due: {{ optional($milestone->target_date)->format('d M Y') }}
                        </small>
                    </div>
                </div>
                @empty
                <div class="p-3 text-muted text-center small">No milestones set.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
