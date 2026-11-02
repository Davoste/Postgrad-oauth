@extends('layouts.public')

@section('title', 'Submitted Referee Report — JOOUST')

@section('content')
<style>
    .submitted-report { min-height: 100vh; padding: 40px 15px 70px; background: #f2f3f5; font-family: Arial, Helvetica, sans-serif; color: #222; }
    .submitted-document { max-width: 920px; margin: 0 auto; background: #fff; border: 1px solid #222; box-shadow: 0 4px 20px rgba(0, 0, 0, .08); }
    .submitted-header { padding: 28px 40px 22px; text-align: center; border-bottom: 2px solid #222; }
    .submitted-header h1 { margin: 0; font-size: 20px; line-height: 1.35; font-weight: 800; text-transform: uppercase; }
    .submitted-header p { margin: 7px 0 0; font-size: 14px; font-weight: 700; text-transform: uppercase; }
    .submitted-header h2 { margin: 17px 0 0; font-size: 16px; text-transform: uppercase; letter-spacing: .7px; }
    .submitted-badge { display: inline-block; margin-top: 12px; padding: 6px 15px; border: 1px solid #287a3d; color: #287a3d; font-size: 10px; font-weight: 700; letter-spacing: 1px; }
    .submitted-intro { padding: 20px 40px; border-bottom: 1px solid #222; font-size: 13px; line-height: 1.65; }
    .submitted-intro p { margin: 0; }
    .submitted-section { padding: 0 40px 28px; }
    .submitted-heading { margin: 26px -40px 20px; padding: 10px 40px; background: #0000ff; color: #fff; border-top: 1px solid #222; border-bottom: 1px solid #222; font-size: 13px; font-weight: 800; text-transform: uppercase; }
    .submitted-grid { display: grid; grid-template-columns: 1fr 1fr; border: 1px solid #333; }
    .submitted-field { min-height: 72px; padding: 12px 14px; border-right: 1px solid #333; border-bottom: 1px solid #333; }
    .submitted-field:nth-child(2n) { border-right: 0; }
    .submitted-field:nth-last-child(-n + 2) { border-bottom: 0; }
    .submitted-caption { display: block; margin-bottom: 7px; font-size: 10px; font-weight: 700; color: #555; text-transform: uppercase; }
    .submitted-value { font-size: 13px; line-height: 1.55; white-space: pre-wrap; }
    .submitted-rating { width: 100%; border-collapse: collapse; font-size: 12px; }
    .submitted-rating th, .submitted-rating td { padding: 10px; border: 1px solid #333; text-align: left; }
    .submitted-rating th { background: #eee; font-weight: 700; }
    .submitted-rating td:last-child { font-weight: 700; text-align: center; }
    .submitted-comment { padding: 14px; border: 1px solid #333; font-size: 13px; line-height: 1.65; white-space: pre-wrap; }
    .submitted-status { margin: 20px 40px 0; padding: 13px 16px; border: 1px solid #287a3d; background: #f1fbf3; color: #216b33; font-size: 12px; }
    .submitted-attachment { width: 100%; height: 620px; border: 1px solid #333; }
    .submitted-footer { display: flex; justify-content: space-between; gap: 15px; padding: 12px 40px; border-top: 1px solid #222; font-size: 9px; color: #666; }
    @media (max-width: 700px) {
        .submitted-report { padding: 15px 0 35px; }
        .submitted-document { border-left: 0; border-right: 0; }
        .submitted-header, .submitted-intro { padding-left: 18px; padding-right: 18px; }
        .submitted-header h1 { font-size: 16px; }
        .submitted-section { padding-left: 18px; padding-right: 18px; }
        .submitted-heading { margin-left: -18px; margin-right: -18px; padding-left: 18px; padding-right: 18px; }
        .submitted-grid { grid-template-columns: 1fr; }
        .submitted-field { border-right: 0; }
        .submitted-field:nth-last-child(-n + 2) { border-bottom: 1px solid #333; }
        .submitted-field:last-child { border-bottom: 0; }
        .submitted-status { margin-left: 18px; margin-right: 18px; }
        .submitted-footer { padding-left: 18px; padding-right: 18px; flex-direction: column; }
    }
</style>

<div class="submitted-report">
    <article class="submitted-document">
        <header class="submitted-header">
            <h1>Jaramogi Oginga Odinga University<br>of Science &amp; Technology</h1>
            <p>Director, Board of Postgraduate Studies</p>
            <h2>Referees Confidential Report</h2>
            <div class="submitted-badge">RESPONSE SUBMITTED</div>
        </header>

        <div class="submitted-intro">
            <p>This confidential referee report was submitted for <strong>{{ $referee->application->full_name ?? 'the candidate' }}</strong>. The completed response is shown below as a read-only record.</p>
        </div>

        <div class="submitted-status">
            <strong>Already submitted.</strong> This response was received on {{ $referee->submitted_at->format('d M Y \a\t H:i') }} and cannot be edited or submitted again.
        </div>

        <section class="submitted-section">
            <h3 class="submitted-heading">Section A: Candidate Information</h3>
            <div class="submitted-grid">
                <div class="submitted-field"><span class="submitted-caption">Name of Candidate</span><div class="submitted-value">{{ $referee->application->full_name ?? '—' }}</div></div>
                <div class="submitted-field"><span class="submitted-caption">Degree Applied For</span><div class="submitted-value">{{ $referee->application->programme->name ?? '—' }}</div></div>
                <div class="submitted-field"><span class="submitted-caption">Department / Faculty</span><div class="submitted-value">{{ $referee->application->programme->department->name ?? '—' }}</div></div>
                <div class="submitted-field"><span class="submitted-caption">Field of Study / Subject</span><div class="submitted-value">{{ $referee->application->programme->field_of_study ?? $referee->application->programme->name ?? '—' }}</div></div>
            </div>
        </section>

        <section class="submitted-section">
            <h3 class="submitted-heading">Section B: Completed Referee Response</h3>
            <div class="submitted-field" style="border:1px solid #333; margin-bottom:20px;">
                <span class="submitted-caption">For how long and in what capacity have you known the candidate?</span>
                <div class="submitted-value">{{ $referee->known_for ?: '—' }}</div>
            </div>

            @php
                $criteria = [
                    'academic_ability' => 'Academic ability',
                    'persistent_independent_study' => 'Ability for persistent & independent study',
                    'productive_scholarship' => 'Potential for productive scholarship',
                    'previous_work' => 'Quality of previous work',
                    'english_expression' => 'Oral and written expression in English',
                ];
                $ratingLabels = [
                    'excellent' => 'Excellent', 'very_good' => 'V. Good', 'good' => 'Good',
                    'average' => 'Average', 'below_average' => 'Below Average', 'unable_to_assess' => 'Unable to Assess',
                ];
            @endphp
            <table class="submitted-rating">
                <thead><tr><th>Assessment Area</th><th>Response</th></tr></thead>
                <tbody>
                    @foreach($criteria as $key => $label)
                        <tr><td>{{ $label }}</td><td>{{ $ratingLabels[$referee->ratings[$key] ?? ''] ?? '—' }}</td></tr>
                    @endforeach
                </tbody>
            </table>

            <div class="submitted-field" style="border:1px solid #333; margin-top:20px;">
                <span class="submitted-caption">Comment freely on the candidate</span>
                <div class="submitted-value">{{ $referee->recommendation ?: '—' }}</div>
            </div>

            <div class="submitted-grid" style="margin-top:20px;">
                <div class="submitted-field"><span class="submitted-caption">Name of Referee</span><div class="submitted-value">{{ $referee->name ?: '—' }}</div></div>
                <div class="submitted-field"><span class="submitted-caption">Designation</span><div class="submitted-value">{{ $referee->designation ?: '—' }}</div></div>
                <div class="submitted-field"><span class="submitted-caption">Referee Address</span><div class="submitted-value">{{ $referee->referee_address ?: '—' }}</div></div>
                <div class="submitted-field"><span class="submitted-caption">Referee Email / Telephone</span><div class="submitted-value">{{ $referee->referee_contact ?: '—' }}</div></div>
            </div>

            @if($referee->attachment_path)
                <div style="margin-top:20px;"><span class="submitted-caption">Supporting Letter</span><iframe class="submitted-attachment" src="{{ asset('storage/' . ltrim($referee->attachment_path, '/')) }}" title="Supporting letter"></iframe></div>
            @endif
        </section>

        <footer class="submitted-footer"><span>JOOUST/AA/BPS/ADM-AF/01B</span><span>Board of Postgraduate Studies</span></footer>
    </article>
</div>
@endsection