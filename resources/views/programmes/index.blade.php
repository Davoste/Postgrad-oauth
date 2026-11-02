@extends('layouts.public')
@section('title', 'Programmes — JOOUST Postgraduate')

@section('content')
<div class="section-divider"></div>
<section class="hero py-5">
    <div class="container">
        <span class="section-label">Board of Postgraduate Studies</span>
        <h1 class="fw-bold mt-2 mb-1">Postgraduate programmes</h1>
        <p class="opacity-75 mb-0">All available programmes by school — with minimum entry requirements, duration and indicative fees.</p>
    </div>
</section>
<div class="section-divider"></div>
<section class="py-5">
    <div class="container" style="max-width:1120px;">
        {{-- quick jump --}}
        <div class="d-flex flex-wrap gap-2 mb-4">
            @foreach($programmes as $school => $list)
                <a href="#{{ \Illuminate\Support\Str::slug($school) }}" class="btn btn-sm btn-outline-primary">{{ $school }} <span class="badge bg-jooust ms-1">{{ $list->count() }}</span></a>
            @endforeach
        </div>

        @forelse($programmes as $school => $list)
            <div id="{{ \Illuminate\Support\Str::slug($school) }}" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <span class="fw-bold text-jooust"><i class="fas fa-graduation-cap me-2"></i>{{ $school }}</span>
                    <span class="badge bg-jooust ms-1">{{ $list->count() }} programme{{ $list->count() > 1 ? 's' : '' }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-uppercase" style="letter-spacing:.04em;">
                                <th class="ps-3" style="width:26%">Programme</th>
                                <th style="width:10%">Level</th>
                                <th style="width:9%">Duration</th>
                                <th style="width:13%">Fees / year</th>
                                <th style="width:13%">Indicative total</th>
                                <th style="width:21%">Minimum requirements</th>
                                <th class="text-end pe-3" style="width:8%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $p)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-semibold text-jooust">{{ $p->name }}</div>
                                        <div class="text-muted small">{{ $p->department->name ?? '' }}</div>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $p->level_label }}</span></td>
                                    <td class="text-nowrap">{{ $p->duration_years }} yr{{ $p->duration_years > 1 ? 's' : '' }}</td>
                                    <td class="text-nowrap">KES {{ number_format($p->tuition_per_year) }}</td>
                                    <td class="text-nowrap">{{ $p->tuition_per_year > 0 ? 'KES ' . number_format($p->total_cost) : '—' }}</td>
                                    <td>
                                        <ul class="small text-muted mb-0 ps-3">
                                            @foreach($p->min_requirements as $req)
                                                <li>{{ $req }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('apply', ['programme' => $p->id]) }}" class="btn btn-sm btn-blue text-nowrap">Apply</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">Programmes will be published shortly.</div>
        @endforelse

        <div class="alert alert-light border small text-muted mt-2">
            <i class="fas fa-circle-info me-1"></i>
            Fees shown are indicative tuition per academic year and exclude statutory and other charges.
            Confirm current fees and specific entry requirements with the Board of Postgraduate Studies before applying.
        </div>
    </div>
</section>
@endsection
