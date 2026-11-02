@extends('layouts.app')
@section('title', 'Executive Dashboard')
@section('page-title', 'Executive Overview')

@section('content')
<p class="text-muted">Institution-wide postgraduate indicators. This view is read-only and shows aggregates only — no individual applicant or student records.</p>

{{-- KPI cards --}}
<div class="row g-3 mb-4">
    @foreach([
        ['Applications', $kpis['applications'], 'fa-file-lines', 'primary'],
        ['Students', $kpis['students'], 'fa-user-graduate', 'success'],
        ['Active programmes', $kpis['programmes'], 'fa-book', 'info'],
        ['Schools', $kpis['schools'], 'fa-building-columns', 'warning'],
    ] as [$label, $val, $icon, $col])
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-{{ $col }} bg-opacity-10 p-3"><i class="fas {{ $icon }} text-{{ $col }} fa-lg"></i></div>
                <div><div class="fs-4 fw-bold">{{ number_format($val) }}</div><div class="text-muted small">{{ $label }}</div></div>
            </div></div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    {{-- Admissions funnel --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Admissions funnel</h6>
            @php $max = max(1, collect($funnel)->max()); @endphp
            @foreach($funnel as $stage => $n)
                <div class="mb-2">
                    <div class="d-flex justify-content-between small"><span>{{ $stage }}</span><span class="fw-semibold">{{ number_format($n) }}</span></div>
                    <div class="progress" style="height:8px;"><div class="progress-bar bg-jooust" style="width: {{ round($n / $max * 100) }}%"></div></div>
                </div>
            @endforeach
        </div></div>
    </div>

    {{-- Applications by status --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Applications by status</h6>
            @php $tot = max(1, $appsByStatus->sum()); @endphp
            @forelse($appsByStatus as $status => $n)
                <div class="mb-2">
                    <div class="d-flex justify-content-between small"><span class="text-capitalize">{{ str_replace('_',' ',$status) }}</span><span class="fw-semibold">{{ number_format($n) }}</span></div>
                    <div class="progress" style="height:8px;"><div class="progress-bar bg-secondary" style="width: {{ round($n / $tot * 100) }}%"></div></div>
                </div>
            @empty
                <div class="text-muted small">No applications yet.</div>
            @endforelse
        </div></div>
    </div>

    {{-- Finance --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Fees (institution-wide)</h6>
            <div class="row text-center g-2">
                <div class="col-4"><div class="fs-6 fw-bold">KES {{ number_format($finance['billed']) }}</div><div class="text-muted small">Billed</div></div>
                <div class="col-4"><div class="fs-6 fw-bold text-success">KES {{ number_format($finance['collected']) }}</div><div class="text-muted small">Collected</div></div>
                <div class="col-4"><div class="fs-6 fw-bold text-danger">KES {{ number_format($finance['outstanding']) }}</div><div class="text-muted small">Outstanding</div></div>
            </div>
            @php $collRate = $finance['billed'] > 0 ? round($finance['collected'] / $finance['billed'] * 100) : 0; @endphp
            <div class="progress mt-3" style="height:8px;"><div class="progress-bar bg-success" style="width: {{ $collRate }}%"></div></div>
            <div class="text-muted small mt-1">Collection rate: {{ $collRate }}%</div>
        </div></div>
    </div>

    {{-- Students by school --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Students by school</h6>
            @php $sMax = max(1, collect($studentsBySchool)->max() ?: 1); @endphp
            @forelse($studentsBySchool as $school => $n)
                <div class="mb-2">
                    <div class="d-flex justify-content-between small"><span>{{ $school }}</span><span class="fw-semibold">{{ $n }}</span></div>
                    <div class="progress" style="height:8px;"><div class="progress-bar bg-info" style="width: {{ round($n / $sMax * 100) }}%"></div></div>
                </div>
            @empty
                <div class="text-muted small">No enrolled students yet.</div>
            @endforelse
        </div></div>
    </div>

    {{-- Programmes by level --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm"><div class="card-body p-4">
            <h6 class="fw-semibold text-jooust mb-3">Programmes by level</h6>
            <div class="row text-center g-2">
                @foreach(['masters'=>"Master's",'phd'=>'PhD','pgd'=>'PG Diploma'] as $lvl=>$lab)
                    <div class="col-4"><div class="fs-5 fw-bold text-jooust">{{ $progByLevel[$lvl] ?? 0 }}</div><div class="text-muted small">{{ $lab }}</div></div>
                @endforeach
            </div>
        </div></div>
    </div>
</div>
@endsection
