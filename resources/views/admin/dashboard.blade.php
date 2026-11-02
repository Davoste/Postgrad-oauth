@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="row g-4 mb-4">
    {{-- Stats --}}
    @php
    $cards = [
        ['label'=>'Total Users',       'value'=>$stats['total_users'],       'icon'=>'fa-users',            'color'=>'primary',  'bg'=>'rgba(59,130,246,.1)'],
        ['label'=>'Students',           'value'=>$stats['total_students'],    'icon'=>'fa-user-graduate',    'color'=>'success',  'bg'=>'rgba(34,197,94,.1)'],
        ['label'=>'Supervisors',        'value'=>$stats['total_supervisors'], 'icon'=>'fa-chalkboard-teacher','color'=>'info',    'bg'=>'rgba(6,182,212,.1)'],
        ['label'=>'Active Theses',      'value'=>$stats['active_theses'],     'icon'=>'fa-book-open',        'color'=>'warning',  'bg'=>'rgba(251,191,36,.1)'],
        ['label'=>'Graduated',          'value'=>$stats['graduated'],         'icon'=>'fa-graduation-cap',   'color'=>'success',  'bg'=>'rgba(34,197,94,.1)'],
        ['label'=>'Meetings This Month','value'=>$stats['meetings_this_month'],'icon'=>'fa-calendar-check', 'color'=>'secondary','bg'=>'rgba(107,114,128,.1)'],
    ];
    @endphp

    @foreach($cards as $card)
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-wrap" style="background: {{ $card['bg'] }}; color: var(--bs-{{ $card['color'] }});">
                    <i class="fas {{ $card['icon'] }}"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4 lh-1">{{ $card['value'] }}</div>
                    <div class="text-muted" style="font-size:12px;">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    {{-- Stage Distribution --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-semibold">Students by Stage</h6>
                <a href="{{ route('admin.monitor') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @foreach($byStage as $stage => $info)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-medium">{{ $info['label'] }}</small>
                        <small class="text-muted">{{ $info['count'] }}</small>
                    </div>
                    @php $pct = $stats['total_students'] > 0 ? round($info['count'] / $stats['total_students'] * 100) : 0; @endphp
                    <div class="progress" style="height:8px; border-radius:4px;">
                        <div class="progress-bar" role="progressbar" style="width:{{ $pct }}%; background:#1a3c6e;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Quick Actions + Recent Activity --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Quick Actions</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Add New User</a>
                <a href="{{ route('admin.monitor') }}"      class="btn btn-outline-secondary"><i class="fas fa-eye me-2"></i>Monitor All Students</a>
                <a href="{{ route('admin.thesis-reviews') }}" class="btn btn-outline-warning"><i class="fas fa-tasks me-2"></i>Pending Thesis Reviews</a>
                <a href="{{ route('admin.reports') }}"      class="btn btn-outline-info"><i class="fas fa-chart-bar me-2"></i>Generate Reports</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Recent Activity</h6>
            </div>
            <div class="card-body p-0">
                @forelse($recentActivity as $activity)
                <div class="px-3 py-2 border-bottom d-flex gap-2 align-items-start">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width:32px;height:32px;">
                        <i class="fas fa-bolt text-primary" style="font-size:12px;"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;">{{ $activity->description }}</div>
                        <div class="text-muted" style="font-size:11px;">
                            {{ optional($activity->causer)->name ?? 'System' }} &bull; {{ $activity->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center py-3 mb-0" style="font-size:13px;">No recent activity.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
