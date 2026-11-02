@extends('layouts.public')
@section('title', 'Recommendation submitted — JOOUST')
@section('content')
<div class="section-divider"></div>
<section class="py-5"><div class="container" style="max-width:680px;"><div class="form-card p-5 text-center">
    <i class="fas fa-circle-check text-success fa-3x mb-3"></i>
    <h1 class="h3">Recommendation submitted</h1>
    <p class="text-muted mb-0">Thank you. Your confidential recommendation for {{ $referee->application->full_name }} has been received by the Board of Postgraduate Studies.</p>
</div></div></section>
@endsection