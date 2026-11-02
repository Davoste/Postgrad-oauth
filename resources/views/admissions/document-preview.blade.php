@extends('layouts.app')
@section('title', $title)
@section('page-title', $title)

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center gap-3">
        <span class="fw-semibold text-truncate">{{ $fileName }}</span>
        @if($downloadUrl)
            <a href="{{ $downloadUrl }}" class="btn btn-sm btn-primary flex-shrink-0">
                <i class="fas fa-download me-1"></i>Download
            </a>
        @endif
    </div>
    <div class="card-body p-0" style="height: calc(100vh - 190px); min-height: 520px;">
        <iframe src="{{ $previewUrl }}" title="{{ $title }}" class="w-100 h-100 border-0"></iframe>
    </div>
</div>
@endsection
