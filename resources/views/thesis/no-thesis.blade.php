@extends('layouts.app')
@section('title', 'Start Your Thesis')
@section('page-title', 'My Thesis')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center py-4">
            <i class="fas fa-graduation-cap text-primary" style="font-size:3rem;opacity:0.5;"></i>
            <h4 class="mt-3 fw-semibold">Start Your Thesis Journey</h4>
            <p class="text-muted mb-0">Submit your initial concept to begin the postgraduate thesis process.</p>
        </div>
    </div>

    {{-- Stage overview --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0 fw-semibold">The Process</h6>
        </div>
        <div class="card-body p-0">
            @php
            $steps = [
                ['icon'=>'fa-lightbulb',      'color'=>'primary',   'title'=>'Concept Development',   'desc'=>'Submit your initial thesis idea and research objectives.'],
                ['icon'=>'fa-file-alt',        'color'=>'success',   'title'=>'Proposal Development',  'desc'=>'Detailed research proposal reviewed by your supervisor.'],
                ['icon'=>'fa-shield-alt',      'color'=>'warning',   'title'=>'Ethical Clearance',     'desc'=>'Ethics committee reviews and approves your research plan.'],
                ['icon'=>'fa-database',        'color'=>'info',      'title'=>'Data Collection',       'desc'=>'Gather and analyse your research data.'],
                ['icon'=>'fa-pencil-alt',      'color'=>'secondary', 'title'=>'Thesis Writing',        'desc'=>'Write and finalise your complete thesis document.'],
                ['icon'=>'fa-calendar-check',  'color'=>'warning',   'title'=>'Intent to Submit',      'desc'=>'Declare your planned submission date.'],
                ['icon'=>'fa-book',            'color'=>'primary',   'title'=>'Submission & Examination','desc'=>'Final thesis submitted and examined.'],
                ['icon'=>'fa-graduation-cap',  'color'=>'success',   'title'=>'Graduation',            'desc'=>'Thesis accepted — congratulations!'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 bg-{{ $step['color'] }} bg-opacity-10"
                     style="width:36px;height:36px;">
                    <i class="fas {{ $step['icon'] }} text-{{ $step['color'] }}" style="font-size:14px;"></i>
                </div>
                <div>
                    <div class="fw-medium" style="font-size:13px;">{{ $i+1 }}. {{ $step['title'] }}</div>
                    <div class="text-muted" style="font-size:12px;">{{ $step['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Concept submission form --}}
    <div class="card border-0 shadow-sm border-primary">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Step 1: Submit Your Concept</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('thesis.submit-concept') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-medium">Thesis Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}" placeholder="Enter your proposed thesis title" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Concept Description <span class="text-danger">*</span></label>
                    <textarea name="description" rows="6"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe your thesis concept, research problem, objectives, and expected contributions…" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Supporting Documents <small class="text-muted">(optional)</small></label>
                    <input type="file" name="documents[]" multiple
                           class="form-control @error('documents.*') is-invalid @enderror"
                           accept=".pdf,.doc,.docx">
                    <div class="form-text">PDF, DOC, DOCX — max 10 MB each</div>
                    @error('documents.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="alert alert-info py-2 small mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Your concept will be reviewed by your assigned supervisor. You will receive feedback within 2–3 weeks.
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>Submit Concept &amp; Begin
                </button>
            </form>
        </div>
    </div>

</div>
</div>
@endsection
