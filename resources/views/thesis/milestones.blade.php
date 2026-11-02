@extends('layouts.app')

@section('title', 'My Milestones')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">My Milestones</h2>
                            <p class="text-muted mb-0">Track your thesis milestones and deadlines</p>
                        </div>
                        <a href="{{ route('thesis.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Milestones by Stage -->
    @if($milestones->count() > 0)
        @foreach($milestones as $stage => $stageMilestones)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">{{ ucfirst(str_replace('_', ' ', $stage)) }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($stageMilestones as $milestone)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 {{ $milestone->status === 'completed' ? 'border-success' : ($milestone->status === 'overdue' ? 'border-danger' : 'border-warning') }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ $milestone->milestone_name }}</h6>
                                            <span class="badge bg-{{ $milestone->status === 'completed' ? 'success' : ($milestone->status === 'overdue' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($milestone->status) }}
                                            </span>
                                        </div>

                                        @if($milestone->description)
                                        <p class="card-text text-muted small mb-3">{{ $milestone->description }}</p>
                                        @endif

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Progress</small>
                                                <small class="text-muted">{{ $milestone->progress_percentage }}%</small>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar {{ $milestone->progress_percentage >= 100 ? 'bg-success' : 'bg-primary' }}"
                                                     style="width: {{ $milestone->progress_percentage }}%"></div>
                                            </div>
                                        </div>

                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Target Date</small>
                                                <strong class="{{ $milestone->target_date->isPast() && $milestone->status !== 'completed' ? 'text-danger' : 'text-dark' }}">
                                                    {{ $milestone->target_date->format('M d, Y') }}
                                                </strong>
                                            </div>
                                            <div class="col-6">
                                                @if($milestone->completed_date)
                                                <small class="text-muted d-block">Completed</small>
                                                <strong class="text-success">{{ $milestone->completed_date->format('M d, Y') }}</strong>
                                                @else
                                                <small class="text-muted d-block">Days Left</small>
                                                <strong class="{{ $milestone->target_date->isPast() ? 'text-danger' : 'text-primary' }}">
                                                    @if($milestone->target_date->isPast())
                                                        Overdue
                                                    @else
                                                        {{ $milestone->target_date->diffInDays(now()) }} days
                                                    @endif
                                                </strong>
                                                @endif
                                            </div>
                                        </div>

                                        @if($milestone->checklist_items)
                                        <div class="mt-3">
                                            <small class="text-muted d-block mb-2">Checklist:</small>
                                            <ul class="list-unstyled small">
                                                @foreach(json_decode($milestone->checklist_items) as $item)
                                                <li><i class="fas fa-check-circle text-success me-1"></i>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-tasks text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No Milestones Set</h5>
                    <p class="text-muted">Your thesis milestones will be set once you have a supervisor assigned.</p>
                    <a href="{{ route('thesis.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Progress Summary -->
    @if($milestones->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Progress Summary</h5>
                </div>
                <div class="card-body">
                    @php
                        $totalMilestones = $milestones->flatten()->count();
                        $completedMilestones = $milestones->flatten()->where('status', 'completed')->count();
                        $overdueMilestones = $milestones->flatten()->where('status', 'overdue')->count();
                        $upcomingMilestones = $milestones->flatten()->where('status', 'pending')->where('target_date', '>', now())->count();
                        $overallProgress = $totalMilestones > 0 ? round(($completedMilestones / $totalMilestones) * 100, 1) : 0;
                    @endphp

                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3">
                                <h3 class="text-primary">{{ $completedMilestones }}</h3>
                                <p class="text-muted mb-0">Completed</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h3 class="text-warning">{{ $totalMilestones - $completedMilestones - $overdueMilestones }}</h3>
                                <p class="text-muted mb-0">In Progress</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h3 class="text-info">{{ $upcomingMilestones }}</h3>
                                <p class="text-muted mb-0">Upcoming</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <h3 class="text-danger">{{ $overdueMilestones }}</h3>
                                <p class="text-muted mb-0">Overdue</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Overall Progress</span>
                            <span>{{ $overallProgress }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $overallProgress }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection