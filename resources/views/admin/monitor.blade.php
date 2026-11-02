@extends('layouts.app')
@section('title', 'Monitor Students')
@section('page-title', 'Student Monitor')

@section('content')
{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-medium">Filter by Stage</label>
                <select name="stage" class="form-select form-select-sm">
                    <option value="">All Stages</option>
                    @foreach($stages as $key => $label)
                        <option value="{{ $key }}" {{ request('stage') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-medium">Filter by Supervisor</label>
                <select name="supervisor_id" class="form-select form-select-sm">
                    <option value="">All Supervisors</option>
                    @foreach($supervisors as $sup)
                        <option value="{{ $sup->id }}" {{ request('supervisor_id') == $sup->id ? 'selected' : '' }}>{{ $sup->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-sm btn-primary me-2"><i class="fas fa-filter me-1"></i>Filter</button>
                <a href="{{ route('admin.monitor') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">All Students — {{ $theses->total() }} records</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Student</th>
                    <th style="font-size:12px;">Reg No</th>
                    <th style="font-size:12px;">Supervisor</th>
                    <th style="font-size:12px;">Current Stage</th>
                    <th style="font-size:12px;">Progress</th>
                    <th style="font-size:12px;">Meetings (This Month)</th>
                    <th style="font-size:12px;">Overdue Milestones</th>
                    <th style="font-size:12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($theses as $thesis)
                <tr>
                    <td>
                        <div class="fw-medium" style="font-size:13px;">{{ $thesis->student->user->name }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ $thesis->student->program }}</div>
                    </td>
                    <td><small class="text-muted">{{ $thesis->student->reg_no }}</small></td>
                    <td>
                        @if($thesis->supervisor)
                            <span style="font-size:13px;">{{ $thesis->supervisor->user->name }}</span>
                        @else
                            <span class="badge bg-warning text-dark">Unassigned</span>
                        @endif
                    </td>
                    <td>
                        @php
                        $stageColors = [
                            'concept_development'=>'secondary','proposal_development'=>'info',
                            'ethical_approval'=>'warning','data_collection'=>'primary',
                            'writing_thesis'=>'info','intent_to_submit'=>'warning',
                            'submitted'=>'primary','examination'=>'warning','graduated'=>'success'
                        ];
                        $color = $stageColors[$thesis->current_stage] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }} stage-badge">{{ $thesis->stage_label }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px;">
                                <div class="progress-bar bg-success" style="width: {{ $thesis->progress_percentage }}%;"></div>
                            </div>
                            <small class="text-muted">{{ number_format($thesis->progress_percentage, 0) }}%</small>
                        </div>
                    </td>
                    <td class="text-center">
                        @php $mt = $thesis->meetings_this_month; @endphp
                        <span class="badge {{ $mt >= 1 ? 'bg-success' : 'bg-danger' }}">{{ $mt }}</span>
                        @if($mt == 0)
                            <i class="fas fa-exclamation-triangle text-danger ms-1" title="No meeting this month"></i>
                        @endif
                    </td>
                    <td class="text-center">
                        @php $od = $thesis->overdue_milestones; @endphp
                        @if($od > 0)
                            <span class="badge bg-danger">{{ $od }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.thesis.detail', $thesis) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(!$thesis->supervisor_id)
                        <a href="{{ route('admin.thesis.allocate-supervisor.form', $thesis) }}" class="btn btn-sm btn-outline-warning ms-1" title="Assign supervisor">
                            <i class="fas fa-user-plus"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No theses found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($theses->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $theses->links() }}
    </div>
    @endif
</div>
@endsection
