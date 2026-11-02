@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Postgraduate Portal')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 d-flex align-items-center gap-3">
        <div class="rounded-circle bg-primary bg-opacity-10 p-3"><i class="fas fa-user-circle text-primary fa-2x"></i></div>
        <div>
            <h5 class="fw-semibold mb-1">Welcome, {{ $user->display_name }}</h5>
            <div class="text-muted small">Role: <span class="badge bg-secondary text-capitalize">{{ str_replace('_',' ', optional($user->roles->first())->name) }}</span></div>
        </div>
    </div>
</div>

<div class="row g-3">
    @can('view_applications')
    <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
        <i class="fas fa-clipboard-check text-primary fa-lg mb-2"></i>
        <h6 class="fw-semibold">Admissions</h6>
        <p class="text-muted small mb-0">Application review and admissions decisions.</p>
    </div></div></div>
    @endcan
    @can('view_clearance')
    <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
        <i class="fas fa-stamp text-success fa-lg mb-2"></i>
        <h6 class="fw-semibold">Clearance &amp; Records</h6>
        <p class="text-muted small mb-0">Fee clearance, transcripts and student records.</p>
    </div></div></div>
    @endcan
    @can('assign_examiner')
    <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body">
        <i class="fas fa-users-cog text-info fa-lg mb-2"></i>
        <h6 class="fw-semibold">Board &amp; Examination</h6>
        <p class="text-muted small mb-0">Examiner assignment and board review.</p>
    </div></div></div>
    @endcan
</div>

<div class="alert alert-light border mt-4 mb-0 small text-muted">
    <i class="fas fa-info-circle me-1"></i>
    Your role's full workspace is being rolled out. Thesis lifecycle, ethics, and supervision
    modules are live; the institutional records interfaces for this role are next.
</div>
@endsection
