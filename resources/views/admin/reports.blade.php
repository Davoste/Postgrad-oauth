@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'System Reports')

@section('content')
{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="fas fa-user-graduate text-primary fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $data['total_students'] }}</div>
                    <div class="text-muted small">Total Students</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3"><i class="fas fa-chalkboard-teacher text-info fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $data['total_supervisors'] }}</div>
                    <div class="text-muted small">Supervisors</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3"><i class="fas fa-graduation-cap text-success fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $data['graduated'] }}</div>
                    <div class="text-muted small">Graduated</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 p-3"><i class="fas fa-exclamation-triangle text-danger fa-lg"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ $data['overdue_milestones'] }}</div>
                    <div class="text-muted small">Overdue Milestones</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Students Per Stage --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-layer-group me-2 text-primary"></i>Students by Stage</h6>
            </div>
            <div class="card-body">
                @foreach($byStage as $stage => $info)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size:13px;">{{ $info['label'] }}</span>
                        <span class="badge bg-primary">{{ $info['count'] }}</span>
                    </div>
                    @php $pct = $data['total_students'] > 0 ? ($info['count'] / $data['total_students']) * 100 : 0; @endphp
                    <div class="progress" style="height:5px;">
                        <div class="progress-bar bg-primary" style="width:{{ $pct }}%;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Supervisor Workload --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="fas fa-users me-2 text-info"></i>Supervisor Workload</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:12px;">Supervisor</th>
                            <th style="font-size:12px;">Dept</th>
                            <th style="font-size:12px;">Active</th>
                            <th style="font-size:12px;">Capacity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supervisorLoad as $sup)
                        <tr>
                            <td style="font-size:13px;">{{ $sup->user->name }}</td>
                            <td><small class="text-muted">{{ $sup->department }}</small></td>
                            <td>
                                <span class="badge {{ $sup->active_students >= $sup->max_capacity ? 'bg-danger' : 'bg-success' }}">
                                    {{ $sup->active_students }}
                                </span>
                            </td>
                            <td><small class="text-muted">{{ $sup->max_capacity }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No supervisors.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Meeting Stats --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold"><i class="fas fa-calendar-check me-2 text-success"></i>Meeting Activity (Last 3 Months)</h6>
    </div>
    <div class="card-body">
        @if($meetingStats->count() > 0)
        <div class="row">
            @foreach($meetingStats as $stat)
            <div class="col-md-4 mb-3">
                <div class="border rounded p-3 text-center">
                    <div class="fs-3 fw-bold text-success">{{ $stat->total }}</div>
                    <div class="text-muted small">{{ date('F Y', mktime(0,0,0,$stat->mo,1,$stat->yr)) }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted mb-0">No meeting data for the last 3 months.</p>
        @endif
    </div>
</div>
@endsection
