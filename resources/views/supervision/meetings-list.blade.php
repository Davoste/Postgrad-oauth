@extends('layouts.app')
@section('title', 'Supervision Meetings')
@section('page-title', 'Supervision Meetings')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">All recorded supervision meetings</p>
    @if(Auth::user()->hasRole('supervisor'))
    <a href="{{ route('supervision.log') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Log New Meeting
    </a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Meetings — {{ $meetings->total() }} records</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Date</th>
                    @if(!Auth::user()->hasRole('student'))
                    <th style="font-size:12px;">Student</th>
                    @endif
                    @if(!Auth::user()->hasRole('supervisor'))
                    <th style="font-size:12px;">Supervisor</th>
                    @endif
                    <th style="font-size:12px;">Agenda</th>
                    <th style="font-size:12px;">Minutes</th>
                    <th style="font-size:12px;">Signatures</th>
                    @if(Auth::user()->hasRole('student'))
                    <th style="font-size:12px;">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($meetings as $meeting)
                <tr>
                    <td><small>{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</small></td>
                    @if(!Auth::user()->hasRole('student'))
                    <td>
                        <div class="fw-medium" style="font-size:13px;">{{ optional(optional($meeting->student)->user)->name ?? 'N/A' }}</div>
                        <div class="text-muted" style="font-size:11px;">{{ optional($meeting->student)->reg_no }}</div>
                    </td>
                    @endif
                    @if(!Auth::user()->hasRole('supervisor'))
                    <td style="font-size:13px;">{{ optional(optional($meeting->supervisor)->user)->name ?? 'N/A' }}</td>
                    @endif
                    <td>
                        <div style="font-size:13px; max-width:220px;" class="text-truncate" title="{{ $meeting->agenda }}">{{ $meeting->agenda }}</div>
                    </td>
                    <td>
                        @if($meeting->minutes)
                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#minutes-{{ $meeting->id }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        @if($meeting->signed_student && $meeting->signed_supervisor)
                            <span class="badge bg-success"><i class="fas fa-check-double me-1"></i>Signed</span>
                        @elseif($meeting->signed_supervisor && !$meeting->signed_student)
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Awaiting Student</span>
                        @else
                            <span class="badge bg-secondary">Incomplete</span>
                        @endif
                    </td>
                    @if(Auth::user()->hasRole('student'))
                    <td>
                        @if($meeting->signed_supervisor && !$meeting->signed_student)
                        <form method="POST" action="{{ route('meetings.sign', $meeting) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning">Sign Off</button>
                        </form>
                        @endif
                    </td>
                    @endif
                </tr>
                @if($meeting->minutes)
                <tr class="collapse" id="minutes-{{ $meeting->id }}">
                    <td colspan="7" class="bg-light">
                        <div class="p-3">
                            <strong class="small">Minutes:</strong>
                            <p class="mb-0 small text-muted mt-1">{{ $meeting->minutes }}</p>
                        </div>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No meetings recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($meetings->hasPages())
    <div class="card-footer bg-white border-top">{{ $meetings->links() }}</div>
    @endif
</div>
@endsection
