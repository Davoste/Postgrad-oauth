@extends('layouts.app')
@section('title', 'Application')
@section('page-title', $application->ref_no)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="fw-semibold mb-0">{{ $application->full_name }}</h5>
                <span class="badge bg-warning text-dark">{{ $application->status_label }}</span>
            </div>
            <dl class="row mb-0 small">
                <dt class="col-sm-4 text-muted fw-normal">Programme</dt><dd class="col-sm-8">{{ $application->programme->name }} ({{ $application->programme->level_label }})</dd>
                <dt class="col-sm-4 text-muted fw-normal">School / Dept</dt><dd class="col-sm-8">{{ optional($application->programme->department->school)->name }} · {{ optional($application->programme->department)->name }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Field of study</dt><dd class="col-sm-8">{{ $application->field_of_study ?? '—' }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Mode / Place</dt><dd class="col-sm-8"><span class="text-capitalize">{{ str_replace('_',' ', $application->mode) }}</span> · {{ $application->campus }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Commence / Complete</dt><dd class="col-sm-8">{{ optional($application->commencement_date)->format('M Y') ?? '—' }} → {{ optional($application->completion_date)->format('M Y') ?? '—' }}</dd>
                @if($application->research_institution)
                    <dt class="col-sm-4 text-muted fw-normal">Research institution</dt><dd class="col-sm-8">{{ $application->research_institution }}</dd>
                @endif

                <dt class="col-12 text-jooust fw-semibold pt-2">Personal</dt>
                <dt class="col-sm-4 text-muted fw-normal">Sex / DOB</dt><dd class="col-sm-8 text-capitalize">{{ $application->sex }} · {{ $application->dob->format('d M Y') }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Nationality / ID</dt><dd class="col-sm-8">{{ $application->nationality }} · {{ $application->id_no ?? '—' }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Marital status</dt><dd class="col-sm-8">{{ $application->marital_status ?? '—' }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Phone / Email</dt><dd class="col-sm-8">{{ $application->phone }} · {{ $application->email }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Postal address</dt><dd class="col-sm-8">{{ $application->postal_address }}</dd>
                @if($application->permanent_address)
                    <dt class="col-sm-4 text-muted fw-normal">Permanent address</dt><dd class="col-sm-8">{{ $application->permanent_address }}</dd>
                @endif

                <dt class="col-12 text-jooust fw-semibold pt-2">Academic background</dt>
                <dt class="col-sm-4 text-muted fw-normal">First degree</dt><dd class="col-sm-8">{{ $application->first_degree_award }} — {{ $application->first_degree_field }}, {{ $application->first_degree_university }} ({{ $application->first_degree_class ?? 'n/a' }}{{ $application->first_degree_year ? ', '.$application->first_degree_year : '' }}){{ $application->first_degree_dates ? ' · '.$application->first_degree_dates : '' }}</dd>
                @if($application->second_degree_university)
                    <dt class="col-sm-4 text-muted fw-normal">Second degree</dt><dd class="col-sm-8">{{ $application->second_degree_award }} — {{ $application->second_degree_field }}, {{ $application->second_degree_university }}{{ $application->second_degree_year ? ' ('.$application->second_degree_year.')' : '' }}</dd>
                @endif
                @if($application->other_qualifications)
                    <dt class="col-sm-4 text-muted fw-normal">Other qualifications</dt><dd class="col-sm-8">{{ $application->other_qualifications }}</dd>
                @endif
                @if($application->research_experience)
                    <dt class="col-sm-4 text-muted fw-normal">Research experience</dt><dd class="col-sm-8">{{ $application->research_experience }}</dd>
                @endif
                @if($application->languages)
                    <dt class="col-sm-4 text-muted fw-normal">Other languages</dt><dd class="col-sm-8">{{ $application->languages }}</dd>
                @endif
                <dt class="col-sm-4 text-muted fw-normal">Funding</dt><dd class="col-sm-8">{{ $application->finance_source ?? '—' }}</dd>
            </dl>

            @if(!empty($application->employment))
                <div class="mt-3">
                    <div class="text-jooust fw-semibold small mb-1">Employment record</div>
                    <table class="table table-sm small mb-0">
                        <thead class="table-light"><tr><th>Position</th><th>Place</th><th>From</th><th>To</th></tr></thead>
                        <tbody>
                            @foreach($application->employment as $job)
                                <tr><td>{{ $job['position'] ?? '' }}</td><td>{{ $job['place'] ?? '' }}</td><td>{{ $job['from'] ?? '' }}</td><td>{{ $job['to'] ?? '' }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div></div>

        <div class="card border-0 shadow-sm mb-4"><div class="card-header bg-white fw-semibold">Referees</div>
        <div class="card-body">
            @forelse($application->applicationReferees as $r)
                <div class="border-bottom py-2 small">
                    <div class="d-flex justify-content-between"><span class="fw-semibold">{{ $r->name }}</span><span class="text-muted">{{ $r->email }}</span></div>
                    @if($r->address)<div class="text-muted">{{ $r->address }}</div>@endif
                    @if($r->submitted_at)
                        <div class="text-success mt-1"><i class="fas fa-check me-1"></i>Submitted {{ $r->submitted_at->format('d M Y H:i') }}</div>
                        @php
                            $ratingLabels = [
                                'excellent' => 'Excellent', 'very_good' => 'V. Good', 'good' => 'Good',
                                'average' => 'Average', 'below_average' => 'Below Average', 'unable_to_assess' => 'Unable to Assess',
                            ];
                            $criteria = [
                                'academic_ability' => 'Academic ability',
                                'persistent_independent_study' => 'Persistent & independent study',
                                'productive_scholarship' => 'Productive scholarship',
                                'previous_work' => 'Previous work',
                                'english_expression' => 'English expression',
                            ];
                        @endphp
                        <dl class="row g-1 mt-2 mb-0 small">
                            <dt class="col-sm-4 text-muted fw-normal">Known for</dt><dd class="col-sm-8">{{ $r->known_for ?: '—' }}</dd>
                            <dt class="col-sm-4 text-muted fw-normal">Designation</dt><dd class="col-sm-8">{{ $r->designation ?: '—' }}</dd>
                            <dt class="col-sm-4 text-muted fw-normal">Address</dt><dd class="col-sm-8">{{ $r->referee_address ?: '—' }}</dd>
                            <dt class="col-sm-4 text-muted fw-normal">Contact</dt><dd class="col-sm-8">{{ $r->referee_contact ?: '—' }}</dd>
                        </dl>
                        <div class="table-responsive mt-2">
                            <table class="table table-sm table-bordered small mb-2">
                                <thead class="table-light"><tr><th>Assessment area</th><th>Response</th></tr></thead>
                                <tbody>
                                    @foreach($criteria as $key => $label)
                                        <tr><td>{{ $label }}</td><td class="fw-semibold">{{ $ratingLabels[$r->ratings[$key] ?? ''] ?? '—' }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-2 bg-light rounded" style="white-space: pre-wrap;"><span class="fw-semibold d-block mb-1">Recommendation</span>{{ $r->recommendation ?: '—' }}</div>
                        @if($r->attachment_path)<a href="{{ route('admissions.referee.attachment', [$application, $r]) }}" class="d-inline-block mt-2 text-decoration-none"><i class="fas fa-eye me-1"></i>View supporting letter</a>@endif
                    @else
                        <div class="text-warning mt-1"><i class="fas fa-clock me-1"></i>Awaiting response</div>
                    @endif
                </div>
            @empty
                @forelse(($application->referees ?? []) as $r)
                    <div class="border-bottom py-2 small"><div class="d-flex justify-content-between"><span class="fw-semibold">{{ $r['name'] ?? '—' }}</span><span class="text-muted">{{ $r['email'] ?? '' }}</span></div></div>
                @empty
                    <div class="text-muted small">No referees listed.</div>
                @endforelse
            @endforelse
        </div></div>

        <div class="card border-0 shadow-sm"><div class="card-header bg-white fw-semibold">Supporting documents</div>
        <div class="card-body">
            @php
                $docs = [
                    'photo'        => ['Passport photo', $application->passport_photo_path],
                    'receipt'      => ['Application receipt', $application->application_receipt_path],
                    'academic'     => ['Academic certificate', $application->academic_certificate_path],
                    'transcript'   => ['Transcript', $application->transcript_path],
                    'professional' => ['Professional certificate', $application->professional_certificate_path],
                ];
                $hasAny = collect($docs)->contains(fn ($d) => !empty($d[1]));
            @endphp
            @if($hasAny)
                <div class="d-flex flex-column gap-2">
                    @foreach($docs as $key => $doc)
                        @if($doc[1])
                            <a href="{{ route('admissions.document', [$application, $key]) }}" class="d-flex justify-content-between align-items-center text-decoration-none small border rounded px-3 py-2">
                                <span><i class="fas fa-paperclip me-2 text-jooust"></i>{{ $doc[0] }}</span>
                                <span class="text-muted"><i class="fas fa-eye me-1"></i>View</span>
                            </a>
                        @endif
                    @endforeach
                </div>
                @if($application->application_no)<div class="text-muted small mt-2">Receipt / Application No: <span class="fw-semibold">{{ $application->application_no }}</span></div>@endif
            @else
                <div class="text-muted small">No documents attached (this application predates document uploads).</div>
            @endif
        </div></div>
    </div>

    <div class="col-lg-4">
        {{-- HOD recommendation --}}
        @can('review_applications')
        @if($application->status === 'submitted' && auth()->user()->isHod())
        <div class="card border-0 shadow-sm mb-3"><div class="card-body p-4">
            <h6 class="fw-semibold mb-3">HOD recommendation</h6>
            <form method="POST" action="{{ route('admissions.hod', $application) }}">
                @csrf
                <textarea name="comment" rows="2" class="form-control form-control-sm mb-2" placeholder="Comment (optional)"></textarea>
                <div class="d-flex gap-2">
                    <button name="decision" value="recommended" class="btn btn-sm btn-primary flex-fill">Recommend</button>
                    <button name="decision" value="rejected" class="btn btn-sm btn-outline-secondary flex-fill">Reject</button>
                </div>
            </form>
        </div></div>
        @endif
        @endcan

        {{-- Director decision --}}
        @can('decide_admission')
        @if($application->status === 'under_review' && auth()->user()->isDirector())
        <div class="card border-0 shadow-sm mb-3"><div class="card-body p-4">
            <h6 class="fw-semibold mb-3">Board decision</h6>
            <form method="POST" action="{{ route('admissions.director', $application) }}">
                @csrf
                <textarea name="comment" rows="2" class="form-control form-control-sm mb-2" placeholder="Comment (optional)"></textarea>
                <div class="d-flex gap-2">
                    <button name="decision" value="admitted" class="btn btn-sm btn-primary flex-fill">Admit</button>
                    <button name="decision" value="rejected" class="btn btn-sm btn-outline-secondary flex-fill">Reject</button>
                </div>
            </form>
        </div></div>
        @endif
        @endcan

        {{-- Review trail --}}
        <div class="card border-0 shadow-sm"><div class="card-body p-4 small">
            <h6 class="fw-semibold mb-3">Review trail</h6>
            <div class="mb-2"><span class="text-muted">HOD:</span> <span class="text-capitalize">{{ $application->hod_decision }}</span>
                @if($application->hod_reviewed_at)<div class="text-muted">{{ optional($application->hod)->name }} · {{ $application->hod_reviewed_at->format('d M Y') }}</div>@endif
                @if($application->hod_comment)<div class="fst-italic">"{{ $application->hod_comment }}"</div>@endif
            </div>
            <div><span class="text-muted">Director:</span> <span class="text-capitalize">{{ $application->director_decision }}</span>
                @if($application->director_reviewed_at)<div class="text-muted">{{ optional($application->director)->name }} · {{ $application->director_reviewed_at->format('d M Y') }}</div>@endif
                @if($application->director_comment)<div class="fst-italic">"{{ $application->director_comment }}"</div>@endif
            </div>
        </div></div>
    </div>
</div>
@endsection
