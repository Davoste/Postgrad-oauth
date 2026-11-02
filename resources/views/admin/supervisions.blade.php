@extends('layouts.app')
@section('title', 'Supervisions')
@section('page-title', 'Supervision Meetings')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-medium">Supervisor</label>
                <select name="supervisor_id" class="form-select form-select-sm">
                    <option value="">All Supervisors</option>
                    @foreach($supervisors as $sup)
                        <option value="{{ $sup->id }}" {{ request('supervisor_id') == $sup->id ? 'selected' : '' }}>{{ $sup->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Month</label>
                <select name="month" class="form-select form-select-sm">
                    <option value="">All Months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Year</label>
                <select name="year" class="form-select form-select-sm">
                    <option value="">All Years</option>
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary me-2"><i class="fas fa-filter me-1"></i>Filter</button>
                <a href="{{ route('admin.supervisions') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Meetings — {{ $meetings->total() }} records</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Student</th>
                    <th style="font-size:12px;">Supervisor</th>
                    <th style="font-size:12px;">Date</th>
                    <th style="font-size:12px;">Agenda</th>
                    <th style="font-size:12px;">Minutes</th>
                    <th style="font-size:12px;">Signatures</th>
                </tr>
            </thead>
            <tbody>
                @forelse($meetings as $meeting)
                <tr>
                    <td>
                        <div class="fw-medium" style="font-size:13px;">{{ optional(optional($meeting->student)->user)->name ?? 'N/A' }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ optional($meeting->student)->reg_no }}</div>
                    </td>
                    <td><span style="font-size:13px;">{{ optional(optional($meeting->supervisor)->user)->name ?? 'N/A' }}</span></td>
                    <td><small>{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</small></td>
                    <td>
                        <div style="font-size:13px; max-width:200px;" class="text-truncate" title="{{ $meeting->agenda }}">{{ $meeting->agenda }}</div>
                    </td>
                    <td>
                        @if($meeting->minutes)
                            <span class="badge bg-success">Logged</span>
                        @else
                            <span class="badge bg-secondary">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($meeting->signed_student && $meeting->signed_supervisor)
                            <span class="badge bg-success"><i class="fas fa-check-double me-1"></i>Fully Signed</span>
                        @elseif($meeting->signed_supervisor && !$meeting->signed_student)
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Awaiting Student</span>
                        @else
                            <span class="badge bg-danger">Incomplete</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No meetings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($meetings->hasPages())
    <div class="card-footer bg-white border-top">{{ $meetings->links() }}</div>
    @endif
</div>
@endsection
