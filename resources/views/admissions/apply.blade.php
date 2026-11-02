@extends('layouts.applynav')
@section('title', 'Apply — JOOUST Postgraduate')

@section('content')

<div class="section-divider"></div>
<section class="hero py-5">
    <div class="container">
        <span class="section-label">Admissions</span>
        <h1 class="fw-bold mt-2 mb-1">Postgraduate application</h1>
        <p class="opacity-75 mb-0">Form JOOUST/AA/BPS/ADM-AF/01A · Complete all required fields and attach the supporting documents.</p>
    </div>
</section>
<div class="section-divider"></div>
@if(isset($user))
<div class="container mt-3" style="max-width:960px;">
    <div class="alert alert-info d-flex align-items-center justify-content-between flex-wrap gap-2 mb-0">
        <span><i class="fas fa-circle-info me-1"></i>You're applying as <strong>{{ $user->name }}</strong> — this application will be linked to your portal account.</span>
        <!-- <a href="{{ route('student.dashboard') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-arrow-left me-1"></i>Back to Dashboard</a> -->
    </div>
</div>
@endif
<section class="py-5">
    <div class="container" style="max-width:960px;">
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="alert alert-light border small text-muted">
            <i class="fas fa-circle-info me-1"></i>
            Attach a passport photo, your application-fee receipt, academic certificates, transcripts and
            (where applicable) professional certificates. Referee assessment forms (ADM-AF/01B) are sent by
            your two referees directly to the Director, BPS.
        </div>

        <form id="application-wizard" method="POST" action="{{ route('apply.store') }}" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- ───────── SECTION A — Personal ───────── --}}
            <fieldset class="form-card wizard-step p-4 mb-4">
                <legend>Section A — Personal details</legend>
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label small fw-semibold">Surname *</label><input name="surname" value="{{ old('surname', $user->surname ?? '') }}" required class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">First name *</label><input name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Other names</label><input name="other_names" value="{{ old('other_names') }}" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Current / postal address *</label><textarea name="postal_address" rows="2" required class="form-control">{{ old('postal_address') }}</textarea></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Permanent address <span class="text-muted">(if different)</span></label><textarea name="permanent_address" rows="2" class="form-control">{{ old('permanent_address') }}</textarea></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Tel / Mobile *</label><input name="phone" value="{{ old('phone') }}" required class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Email *</label><input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Year of birth *</label><input type="number" name="dob" value="{{ old('dob') }}" min="1900" max="{{ date('Y') - 18 }}" placeholder="YYYY" required class="form-control"></div>
                    <div class="col-md-3"><label class="form-label small fw-semibold">Sex *</label>
                        <select name="sex" required class="form-select">
                            <option value="">Select…</option>
                            <option value="male" @selected(old('sex')==='male')>Male</option>
                            <option value="female" @selected(old('sex')==='female')>Female</option>
                        </select>
                    </div>
                    <div class="col-md-3"><label class="form-label small fw-semibold">Citizenship *</label><input name="nationality" value="{{ old('nationality','Kenyan') }}" required class="form-control"></div>
                    <div class="col-md-3"><label class="form-label small fw-semibold">ID / Passport No.</label><input name="id_no" value="{{ old('id_no', $user->id_no ?? '') }}" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label small fw-semibold">Marital status</label><input name="marital_status" value="{{ old('marital_status') }}" class="form-control"></div>
                </div>
            </fieldset>

            {{-- ───────── SECTION B — Academic background ───────── --}}
            <fieldset class="form-card wizard-step p-4 mb-4">
                <legend>Section B — Academic &amp; professional background</legend>

                <div class="fw-semibold text-jooust mb-2">First degree</div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label class="form-label small fw-semibold">University attended *</label><input name="first_degree_university" value="{{ old('first_degree_university') }}" required class="form-control"></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Field of study *</label><input name="first_degree_field" value="{{ old('first_degree_field') }}" placeholder="e.g. Economics, Physics" required class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Dates attended *</label><input name="first_degree_dates" value="{{ old('first_degree_dates') }}" placeholder="e.g. 2015–2019" required class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Degree awarded *</label><input name="first_degree_award" value="{{ old('first_degree_award') }}" placeholder="e.g. BSc Upper 2nd Class" required class="form-control"></div>
                    <div class="col-md-2"><label class="form-label small fw-semibold">Class *</label><input name="first_degree_class" value="{{ old('first_degree_class') }}" required class="form-control"></div>
                    <div class="col-md-2"><label class="form-label small fw-semibold">Year awarded *</label><input type="number" name="first_degree_year" value="{{ old('first_degree_year') }}" required class="form-control"></div>
                </div>

                <div class="fw-semibold text-jooust mb-2">Second degree <span class="text-muted fw-normal small">(if any)</span></div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label class="form-label small fw-semibold">University attended</label><input name="second_degree_university" value="{{ old('second_degree_university') }}" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Field of study</label><input name="second_degree_field" value="{{ old('second_degree_field') }}" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Dates attended</label><input name="second_degree_dates" value="{{ old('second_degree_dates') }}" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Degree awarded</label><input name="second_degree_award" value="{{ old('second_degree_award') }}" class="form-control"></div>
                    <div class="col-md-2"><label class="form-label small fw-semibold">Year</label><input type="number" name="second_degree_year" value="{{ old('second_degree_year') }}" class="form-control"></div>
                </div>

                <div class="row g-3">
                    <div class="col-12"><label class="form-label small fw-semibold">Other degrees / diplomas <span class="text-muted">(where applicable)</span></label><textarea name="other_qualifications" rows="2" class="form-control">{{ old('other_qualifications') }}</textarea></div>
                    <div class="col-12"><label class="form-label small fw-semibold">Research experience <span class="text-muted">(publications, reports, dissertation, thesis)</span></label><textarea name="research_experience" rows="3" class="form-control">{{ old('research_experience') }}</textarea></div>
                </div>

                <div class="fw-semibold text-jooust mt-3 mb-2">Employment record</div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light"><tr><th style="width:35%">Position</th><th style="width:35%">Place of employment</th><th>From</th><th>To</th></tr></thead>
                        <tbody>
                            @for($i = 0; $i < 3; $i++)
                                <tr>
                                    <td><input name="employment[{{ $i }}][position]" value="{{ old("employment.$i.position") }}" class="form-control form-control-sm"></td>
                                    <td><input name="employment[{{ $i }}][place]" value="{{ old("employment.$i.place") }}" class="form-control form-control-sm"></td>
                                    <td><input name="employment[{{ $i }}][from]" value="{{ old("employment.$i.from") }}" class="form-control form-control-sm" placeholder="Year"></td>
                                    <td><input name="employment[{{ $i }}][to]" value="{{ old("employment.$i.to") }}" class="form-control form-control-sm" placeholder="Year"></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="row g-3">
                    <div class="col-12"><label class="form-label small fw-semibold">Competency in other languages <span class="text-muted">(other than English)</span></label><input name="languages" value="{{ old('languages') }}" class="form-control"></div>
                </div>
            </fieldset>

            {{-- ───────── SECTION C — Programme applied for ───────── --}}
            <fieldset class="form-card wizard-step p-4 mb-4">
                <legend>Section C — Programme applied for</legend>
                <div class="row g-3">
                    <div class="col-md-8"><label class="form-label small fw-semibold">Programme (degree) *</label>
                        <select name="programme_id" required class="form-select">
                            <option value="">Select a programme…</option>
                            @foreach($programmes->groupBy(fn($p) => $p->department->school->name) as $schoolName => $progs)
                                <optgroup label="{{ $schoolName }}">
                                    @foreach($progs as $p)
                                        <option value="{{ $p->id }}" {{ ((int) old('programme_id', $selected)===$p->id) ? 'selected' : ''}}>{{ $p->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="form-text">The School and Department are determined by the programme you select.</div>
                    </div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Field of study / subject</label><input name="field_of_study" value="{{ old('field_of_study') }}" class="form-control"></div>

                    <div class="col-md-6"><label class="form-label small fw-semibold">Mode of study *</label>
                        <select name="mode" required class="form-select">
                            <option value="full_time" @selected(old('mode')==='full_time')>Full time</option>
                            <option value="part_time" @selected(old('mode')==='part_time')>Part time</option>
                            <option value="school_based" @selected(old('mode')==='school_based')>School based</option>
                            <option value="weekend_intensive" @selected(old('mode')==='weekend_intensive')>Weekend / intensive</option>
                        </select>
                    </div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Place of study *</label>
                        <select name="campus" required class="form-select">
                            <option value="JOOUST Main Campus, Bondo" @selected(old('campus')==='JOOUST Main Campus, Bondo')>JOOUST Main Campus, Bondo</option>
                            <option value="Kisumu Campus" @selected(old('campus')==='Kisumu Campus')>Kisumu Campus</option>
                            <option value="Other" @selected(old('campus')==='Other')>Other (specify below)</option>
                        </select>
                        <input name="campus_other" value="{{ old('campus_other') }}" class="form-control mt-2" placeholder="If other, specify">
                    </div>

                    <div class="col-md-4"><label class="form-label small fw-semibold">Proposed commencement</label><input type="date" name="commencement_date" value="{{ old('commencement_date') }}" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Expected completion</label><input type="date" name="completion_date" value="{{ old('completion_date') }}" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Source of funding</label><input name="finance_source" value="{{ old('finance_source') }}" placeholder="e.g. Self-sponsored" class="form-control"></div>

                    <div class="col-md-8"><label class="form-label small fw-semibold">Institution where research will be done <span class="text-muted">(if not JOOUST)</span></label><input name="research_institution" value="{{ old('research_institution') }}" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label small fw-semibold">Application (receipt) No.</label><input name="application_no" value="{{ old('application_no') }}" class="form-control"></div>
                </div>
            </fieldset>

            {{-- ───────── Referees ───────── --}}
            <fieldset class="form-card wizard-step p-4 mb-4">
                <legend>Academic referees</legend>
                <p class="text-muted small">Two academic referees are required. Email is mandatory — referees will be sent the assessment form.</p>
                @foreach([1, 2] as $n)
                    <div class="row g-3 mb-2">
                        <div class="col-md-4"><label class="form-label small fw-semibold">Referee {{ $n }} name *</label><input name="referee_{{ $n }}_name" value="{{ old("referee_{$n}_name") }}" required class="form-control"></div>
                        <div class="col-md-4"><label class="form-label small fw-semibold">Address</label><input name="referee_{{ $n }}_address" value="{{ old("referee_{$n}_address") }}" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label small fw-semibold">Email *</label><input type="email" name="referee_{{ $n }}_email" value="{{ old("referee_{$n}_email") }}" required class="form-control"></div>
                    </div>
                @endforeach
            </fieldset>

            {{-- ───────── Supporting documents ───────── --}}
            <fieldset class="form-card wizard-step p-4 mb-4">
                <legend>Supporting documents</legend>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label small fw-semibold">Passport-size photo *</label><input type="file" name="passport_photo" accept="image/*" required class="form-control"><div class="form-text">JPG / PNG, max 2 MB.</div></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Application-fee receipt *</label><input type="file" name="application_receipt" accept=".pdf,image/*" required class="form-control"><div class="form-text">PDF or image, max 4 MB.</div></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Academic certificate(s) *</label><input type="file" name="academic_certificate" accept=".pdf,image/*" required class="form-control"><div class="form-text">Combine into one PDF if multiple. Max 20 MB.</div></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Academic transcript(s) *</label><input type="file" name="transcript" accept=".pdf" required class="form-control"><div class="form-text">PDF, max 20 MB.</div></div>
                    <div class="col-md-6"><label class="form-label small fw-semibold">Professional certificate(s)</label><input type="file" name="professional_certificate" accept=".pdf,image/*" class="form-control"><div class="form-text">Optional. PDF or image, max 8 MB.</div></div>
                </div>
            </fieldset>

            <div class="d-flex justify-content-between align-items-center gap-3">
                <p class="text-muted small mb-0">By submitting you confirm the information provided is accurate.</p>
                <div class="d-flex gap-2">
                    <button type="button" id="wizard-back" class="btn btn-outline-secondary px-4">Back</button>
                    <button type="button" id="wizard-next" class="btn btn-blue px-4">Next</button>
                    <button type="submit" id="wizard-submit" class="btn btn-gold px-4">Submit application</button>
                </div>
            </div>
        </form>
    </div>
</section>
<div class="section-divider"></div>
@endsection

@section('styles')
<style>
    .wizard-step { display: none; }
    .wizard-step.is-active { display: block; }
    .wizard-progress { display:flex; gap:.5rem; margin-bottom:1.5rem; }
    .wizard-progress span { flex:1; height:5px; border-radius:3px; background:#dbe3ed; }
    .wizard-progress span.is-active, .wizard-progress span.is-complete { background:var(--jooust-blue); }
    @media (max-width:576px) { .wizard-progress { gap:.25rem; } }
</style>
@endsection

@section('scripts')
<script>
(() => {
    const form = document.getElementById('application-wizard');
    const steps = [...form.querySelectorAll('.wizard-step')];
    const progress = document.createElement('div');
    progress.className = 'wizard-progress';
    steps.forEach(() => progress.appendChild(document.createElement('span')));
    form.insertBefore(progress, steps[0]);

    const back = document.getElementById('wizard-back');
    const next = document.getElementById('wizard-next');
    const submit = document.getElementById('wizard-submit');
    let current = 0;

    const validateStep = (step) => {
        const fields = [...step.querySelectorAll('input, select, textarea')]
            .filter(field => !field.disabled && field.type !== 'button');
        const invalid = fields.find(field => !field.checkValidity());

        if (invalid) {
            invalid.focus();
            invalid.reportValidity();
            return false;
        }

        return true;
    };

    const render = () => {
        steps.forEach((step, index) => step.classList.toggle('is-active', index === current));
        [...progress.children].forEach((item, index) => item.className = index <= current ? (index < current ? 'is-complete' : 'is-active') : '');
        back.hidden = current === 0;
        next.hidden = current === steps.length - 1;
        submit.hidden = current !== steps.length - 1;
        window.scrollTo({ top: form.offsetTop - 30, behavior: 'smooth' });
    };

    next.addEventListener('click', () => {
        if (!validateStep(steps[current])) return;
        current = Math.min(current + 1, steps.length - 1);
        render();
    });
    back.addEventListener('click', () => { current = Math.max(current - 1, 0); render(); });
    form.addEventListener('submit', event => {
        const invalidStep = steps.findIndex(step => !validateStep(step));
        if (invalidStep !== -1) {
            event.preventDefault();
            current = invalidStep;
            render();
            validateStep(steps[current]);
        }
    });

    const firstInvalid = form.querySelector('.is-invalid, :invalid');
    if (firstInvalid) {
        const invalidStep = steps.findIndex(step => step.contains(firstInvalid));
        if (invalidStep >= 0) current = invalidStep;
    }
    render();
})();
</script>
@endsection
