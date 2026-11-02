<?php

namespace App\Http\Controllers;

use App\Models\{Application, ApplicationReferee, Department, Programme, Student, User};
use App\Notifications\ApplicationStatusNotification;
use App\Notifications\RefereeRecommendationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Notification, Storage};
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    // ── Public: application form (registration) ──────────────────────────
    public function create(Request $request)
    {
        $programmes = Programme::active()->where('applications_open', true)
            ->with('department.school')->orderBy('name')->get();
        $selected = $request->get('programme');
        return view('admissions.apply', compact('programmes', 'selected'));
    }

    /**
     * Signed-in users who registered on the portal apply through this
     * shortcut so their new application gets linked to their account and
     * their name / email / ID are pre-filled from their profile.
     */
    public function createForUser(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmitted()) {
            return redirect()->route('student.dashboard')
                ->with('success', 'You have already been admitted — no need to apply again.');
        }

        $programmes = Programme::active()->where('applications_open', true)
            ->with('department.school')->orderBy('name')->get();
        $selected = $request->get('programme');

        return view('admissions.apply', compact('programmes', 'selected', 'user'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Section A — personal
            'surname'      => 'required|string|max:100',
            'first_name'   => 'required|string|max:100',
            'other_names'  => 'nullable|string|max:100',
            'dob'          => 'required|integer|digits:4|min:1920|max:' . (date('Y') - 18),
            'sex'          => 'required|in:male,female',
            'nationality'  => 'required|string|max:60',
            'id_no'        => 'nullable|string|max:30',
            'marital_status' => 'nullable|string|max:30',
            'phone'        => 'required|string|max:20',
            'email'        => [
                'required', 'email', 'max:150',
                // A logged-in user re-using their own registered email
                // (the normal case now that self-registration exists)
                // shouldn't trip the "already taken" rule against their
                // own account.
                Rule::unique('users', 'email')->ignore(optional($request->user())->id),
            ],
            'postal_address'    => 'required|string|max:255',
            'permanent_address' => 'nullable|string|max:255',

            // Section B — academic background
            'first_degree_university' => 'required|string|max:150',
            'first_degree_field'      => 'required|string|max:120',
            'first_degree_dates'      => 'required|string|max:40',
            'first_degree_award'      => 'required|string|max:150',
            'first_degree_class'      => 'required|string|max:60',
            'first_degree_year'       => 'required|integer|min:1970|max:' . date('Y'),
            'second_degree_university' => 'nullable|string|max:150',
            'second_degree_field'      => 'nullable|string|max:120',
            'second_degree_dates'      => 'nullable|string|max:40',
            'second_degree_award'      => 'nullable|string|max:150',
            'second_degree_year'       => 'nullable|integer|min:1970|max:' . date('Y'),
            'other_qualifications'     => 'nullable|string',
            'research_experience'      => 'nullable|string',
            'languages'                => 'nullable|string|max:200',
            'employment'               => 'nullable|array',
            'employment.*.position'    => 'nullable|string|max:120',
            'employment.*.place'       => 'nullable|string|max:150',
            'employment.*.from'        => 'nullable|string|max:20',
            'employment.*.to'          => 'nullable|string|max:20',

            // Section C — programme & study
            'programme_id'  => 'required|exists:programmes,id',
            'field_of_study'=> 'nullable|string|max:150',
            'mode'          => 'required|in:full_time,part_time,school_based,weekend_intensive',
            'campus'        => 'required|string|max:120',
            'campus_other'  => 'nullable|string|max:120',
            'commencement_date'    => 'nullable|date',
            'completion_date'      => 'nullable|date|after:commencement_date',
            'research_institution' => 'nullable|string|max:150',
            'finance_source'       => 'nullable|string|max:150',
            'application_no'       => 'nullable|string|max:60',

            // Referees (name / address / mandatory email)
            'referee_1_name'    => 'required|string|max:120',
            'referee_1_address' => 'nullable|string|max:200',
            'referee_1_email'   => 'required|email|max:150',
            'referee_2_name'    => 'required|string|max:120',
            'referee_2_address' => 'nullable|string|max:200',
            'referee_2_email'   => 'required|email|max:150',

            // Supporting documents
            'passport_photo'          => 'required|image|max:2048',
            'application_receipt'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'academic_certificate'    => 'required|file|mimes:pdf|max:20480',
            'transcript'              => 'required|file|mimes:pdf|max:20480',
            'professional_certificate'=> 'nullable|file|mimes:pdf|max:8192',
        ]);

        // Keep the existing date column while collecting only the birth year.
        $data['dob'] .= '-01-01';

        $application = DB::transaction(function () use ($request, $data) {
            // Guard against duplicate submissions (double-click / proxy retry):
            // reuse a matching application created in the last few minutes.
            $recent = Application::where('email', $data['email'])
                ->where('programme_id', $data['programme_id'])
                ->where('created_at', '>=', now()->subMinutes(10))
                ->latest()->first();
            if ($recent) {
                return $recent;
            }

            $payload = collect($data)->except([
                'referee_1_name', 'referee_1_address', 'referee_1_email',
                'referee_2_name', 'referee_2_address', 'referee_2_email',
                'employment', 'campus_other',
                'passport_photo', 'application_receipt', 'academic_certificate',
                'transcript', 'professional_certificate',
            ])->toArray();

            // "Other" place of study → use the free-text value
            if (($data['campus'] ?? null) === 'Other' && !empty($data['campus_other'])) {
                $payload['campus'] = $data['campus_other'];
            }

            $payload['referees'] = [
                ['name' => $data['referee_1_name'], 'address' => $data['referee_1_address'] ?? null, 'email' => $data['referee_1_email']],
                ['name' => $data['referee_2_name'], 'address' => $data['referee_2_address'] ?? null, 'email' => $data['referee_2_email']],
            ];

            $payload['employment'] = collect($data['employment'] ?? [])
                ->filter(fn ($r) => !empty($r['position']) || !empty($r['place']))
                ->map(fn ($r) => [
                    'position' => $r['position'] ?? null,
                    'place'    => $r['place'] ?? null,
                    'from'     => $r['from'] ?? null,
                    'to'       => $r['to'] ?? null,
                ])->values()->all();

            // Store uploads on the public disk
            $payload['passport_photo_path']       = $request->file('passport_photo')->store('applications/photos', 'public');
            $payload['application_receipt_path']  = $request->file('application_receipt')->store('applications/receipts', 'public');
            $payload['academic_certificate_path'] = $request->file('academic_certificate')->store('applications/certificates', 'public');
            $payload['transcript_path']           = $request->file('transcript')->store('applications/transcripts', 'public');
            if ($request->hasFile('professional_certificate')) {
                $payload['professional_certificate_path'] = $request->file('professional_certificate')->store('applications/certificates', 'public');
            }

            $payload['status'] = 'submitted';
            $payload['user_id'] = optional($request->user())->id;

            $application = Application::create($payload);
            $application->applicationReferees()->createMany([
                ['name' => $data['referee_1_name'], 'address' => $data['referee_1_address'] ?? null, 'email' => $data['referee_1_email']],
                ['name' => $data['referee_2_name'], 'address' => $data['referee_2_address'] ?? null, 'email' => $data['referee_2_email']],
            ]);

            return $application;
        });

        // Confirmation to applicant + HOD alert — only for a genuinely new application
        if ($application->wasRecentlyCreated) {
            Notification::route('mail', [$application->email => $application->full_name])
                ->notify(new ApplicationStatusNotification($application, 'submitted'));

            $hod = optional($application->programme->department)->hod;
            if ($hod) {
                $hod->notify(new ApplicationStatusNotification($application, 'hod_review_required'));
            }

            $application->applicationReferees->each(function (ApplicationReferee $referee) {
                Notification::route('mail', [$referee->email => $referee->name])
                    ->notify(new RefereeRecommendationRequest($referee));
            });
        }

        return redirect()->route('apply.success', $application->tracking_token);
    }

    public function refereeShow(string $token)
    {
        $referee = ApplicationReferee::with('application.programme')
            ->where('token', $token)->firstOrFail();

        $view = $referee->submitted_at ? 'referees.submitted' : 'referees.respond';

        return view($view, compact('referee'));
    }

    public function refereeRespond(Request $request, string $token)
    {
        $referee = ApplicationReferee::where('token', $token)->firstOrFail();
        abort_if($referee->submitted_at, 403, 'This recommendation has already been submitted.');

        $data = $request->validate([
            'known_for' => 'required|string|min:20|max:3000',
            'ratings' => 'required|array',
            'ratings.academic_ability' => 'required|in:excellent,very_good,good,average,below_average,unable_to_assess',
            'ratings.persistent_independent_study' => 'required|in:excellent,very_good,good,average,below_average,unable_to_assess',
            'ratings.productive_scholarship' => 'required|in:excellent,very_good,good,average,below_average,unable_to_assess',
            'ratings.previous_work' => 'required|in:excellent,very_good,good,average,below_average,unable_to_assess',
            'ratings.english_expression' => 'required|in:excellent,very_good,good,average,below_average,unable_to_assess',
            'recommendation' => 'required|string|min:50|max:10000',
            'referee_name' => 'required|string|max:150',
            'designation' => 'nullable|string|max:150',
            'referee_address' => 'nullable|string|max:500',
            'referee_contact' => 'nullable|string|max:150',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $attachmentPath = $request->hasFile('attachment')
            ? $request->file('attachment')->store('applications/referee-recommendations', 'public')
            : null;

        $referee->update([
            'name' => $data['referee_name'],
            'known_for' => $data['known_for'],
            'ratings' => $data['ratings'],
            'designation' => $data['designation'] ?? null,
            'referee_address' => $data['referee_address'] ?? null,
            'referee_contact' => $data['referee_contact'] ?? null,
            'recommendation' => $data['recommendation'],
            'attachment_path' => $attachmentPath,
            'submitted_at' => now(),
        ]);

        return view('referees.thanks', compact('referee'));
    }

    public function success(string $token)
    {
        $application = Application::where('tracking_token', $token)->firstOrFail();
        return view('admissions.apply-success', compact('application'));
    }

    // ── Public: track status ─────────────────────────────────────────────
    public function track(string $token)
    {
        $application = Application::with('programme.department.school', 'acceptanceForm')
            ->where('tracking_token', $token)->firstOrFail();
        return view('admissions.track', compact('application'));
    }

    // ── Public: accept / decline offer ───────────────────────────────────
    public function respondToOffer(Request $request, string $token)
    {
        $application = Application::where('tracking_token', $token)->firstOrFail();
        abort_unless($application->status === 'offer_made', 403, 'No active offer to respond to.');

        $data = $request->validate([
            'decision' => 'required|in:accept,decline',
            'decline_reason' => 'required_if:decision,decline|nullable|string',
            'next_of_kin_name' => 'nullable|string|max:120',
            'next_of_kin_phone' => 'nullable|string|max:20',
            'county' => 'nullable|string|max:60',
        ]);

        $student = null;
        DB::transaction(function () use ($application, $data, &$student) {
            $application->acceptanceForm()->create($data);
            $application->update([
                'status' => $data['decision'] === 'accept' ? 'accepted' : 'declined',
            ]);
            if ($data['decision'] === 'accept') {
                $student = $this->enrollStudent($application);
            }
        });

        // Moodle provisioning runs AFTER the enrolment commits and is guarded, so a
        // Moodle outage can never roll back or block a student's enrolment.
        if ($student) {
            try {
                \App\Jobs\ProvisionMoodleUser::dispatch($student->id);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Moodle provisioning dispatch failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('track', $token)->with('success', $data['decision'] === 'accept'
            ? 'Offer accepted. Your student account has been created — check your email for login details.'
            : 'You have declined the offer.');
    }

    /**
     * Render the admission letter Blade view to a properly formatted PDF using
     * dompdf. This preserves the template's CSS (tables, colors, watermark,
     * logo) instead of flattening everything to plain text lines.
     */
    private function generateAdmissionLetterPdf(Application $application, string $admNo, ?string $password = null): string
    {
        $pdf = Pdf::loadView('emails.admission-letter-pdf', [
            'application' => $application,
            'admNo'       => $admNo,
            'password'    => $password,
            'programme'   => $application->programme,
        ])->setPaper('a4');

        return $pdf->output();
    }

    private function enrollStudent(Application $application): Student
    {
        $programme = $application->programme;
        $admNo = $this->generateAdmNo($programme);

        // If the applicant already registered a portal account and applied
        // while signed in, link that existing account rather than minting a
        // new one — they already know their own password.
        $existingUser = $application->user_id ? User::find($application->user_id) : null;
        $tempPassword = $existingUser ? null : Str::random(10);

        $user = $existingUser ?: User::create([
            'name'              => $application->full_name,
            'email'             => $application->email,
            'phone'             => $application->phone,
            'id_no'             => $application->id_no,
            'password'          => Hash::make($tempPassword),
            'email_verified_at' => now(),
        ]);

        if (! $user->hasRole('student')) {
            $user->assignRole('student');
        }

        $student = Student::create([
            'user_id'        => $user->id,
            'application_id' => $application->id,
            'programme_id'   => $programme->id,
            'reg_no'         => $admNo,
            'program'        => $programme->name,
            'admission_year' => (int) date('Y'),
            'mode'           => $application->mode,
            'status'         => 'active',
        ]);

        $pdfContent = $this->generateAdmissionLetterPdf($application, $admNo, $tempPassword);

        Notification::route('mail', [$user->email => $user->name])
            ->notify(new ApplicationStatusNotification($application, 'enrolled', [
                 'adm_no' => $admNo,
                 'password' => $tempPassword,
                 'pdf_content' => $pdfContent,
                 'pdf_name' => "{$admNo}-admission-letter.pdf",
            ]));

        return $student;
    }

    private function generateAdmNo(Programme $programme): string
    {
        $prefix = 'BPS/' . strtoupper($programme->level) . '/' . date('Y');
        $count = Student::where('reg_no', 'like', "{$prefix}%")->count() + 1;
        return $prefix . '/' . str_pad((string) $count, 3, '0', STR_PAD_LEFT);
    }

    // ── Internal: list applications (HOD / Director / Registrar / Admin) ──
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Application::with('programme.department');

        if ($user->isHod()) {
            $deptIds = Department::where('hod_id', $user->id)->pluck('id');
            $query->whereHas('programme', fn ($q) => $q->whereIn('department_id', $deptIds));
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $applications = $query->latest()->paginate(20)->withQueryString();
        return view('admissions.index', compact('applications'));
    }

    public function show(Application $application)
    {
        $application->load('programme.department.school', 'hod', 'director', 'acceptanceForm', 'applicationReferees');
        return view('admissions.show', compact('application'));
    }

    private function documentDetails(Application $application, string $type): array
    {
        $map = [
            'photo'        => ['passport_photo_path', 'passport-photo'],
            'receipt'      => ['application_receipt_path', 'application-receipt'],
            'academic'     => ['academic_certificate_path', 'academic-certificate'],
            'transcript'   => ['transcript_path', 'transcript'],
            'professional' => ['professional_certificate_path', 'professional-certificate'],
        ];
        abort_unless(isset($map[$type]), 404);

        [$column, $label] = $map[$type];
        $path = $application->{$column};
        abort_if(!$path || !Storage::disk('public')->exists($path), 404, 'File not found.');

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $name = str_replace(['/', '\\'], '-', $application->ref_no) . '-' . $label . ($extension ? '.' . $extension : '');

        return [$path, $name, $label];
    }

    public function previewDocument(Application $application, string $type)
    {
        [$path, $name, $label] = $this->documentDetails($application, $type);

        return view('admissions.document-preview', [
            'title' => $label,
            'previewUrl' => route('admissions.document.content', [$application, $type]),
            'downloadUrl' => route('admissions.document.download', [$application, $type]),
            'fileName' => $name,
        ]);
    }

    public function documentContent(Application $application, string $type)
    {
        [$path] = $this->documentDetails($application, $type);

        return response()->file(Storage::disk('public')->path($path), [
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /** Download a supporting document only after the reviewer explicitly asks. */
    public function downloadDocument(Application $application, string $type)
    {
        [$path, $name] = $this->documentDetails($application, $type);

        return Storage::disk('public')->download($path, $name);
    }

    private function refereeAttachmentDetails(Application $application, ApplicationReferee $referee): array
    {
        abort_unless($referee->application_id === $application->id, 404);
        abort_if(!$referee->attachment_path || !Storage::disk('public')->exists($referee->attachment_path), 404, 'File not found.');

        $name = str_replace(['/', '\\'], '-', $application->ref_no) . '-referee-recommendation.' . pathinfo($referee->attachment_path, PATHINFO_EXTENSION);
        return [$referee->attachment_path, $name];
    }

    public function previewRefereeAttachment(Application $application, ApplicationReferee $referee)
    {
        [$path, $name] = $this->refereeAttachmentDetails($application, $referee);

        return view('admissions.document-preview', [
            'title' => 'Referee supporting letter',
            'previewUrl' => route('admissions.referee.attachment.content', [$application, $referee]),
            'downloadUrl' => route('admissions.referee.attachment.download', [$application, $referee]),
            'fileName' => $name,
        ]);
    }

    public function refereeAttachmentContent(Application $application, ApplicationReferee $referee)
    {
        [$path] = $this->refereeAttachmentDetails($application, $referee);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === 'docx') {
            return view('admissions.referee-docx-preview', [
                'title' => 'Referee supporting letter',
                'paragraphs' => $this->extractDocxParagraphs(Storage::disk('public')->path($path)),
            ]);
        }

        return response()->file(Storage::disk('public')->path($path), [
            'Content-Type' => Storage::disk('public')->mimeType($path),
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function extractDocxParagraphs(string $path): array
    {
        $archive = new \ZipArchive();
        abort_unless($archive->open($path) === true, 422, 'The referee document could not be opened.');

        $xml = $archive->getFromName('word/document.xml');
        $archive->close();
        abort_if($xml === false, 422, 'The referee document content could not be read.');

        $document = new \DOMDocument();
        $document->loadXML($xml, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new \DOMXPath($document);
        $paragraphs = [];

        foreach ($xpath->query('//*[local-name()="p"]') as $paragraph) {
            $text = '';
            foreach ($xpath->query('.//*[local-name()="t"]', $paragraph) as $textNode) {
                $text .= $textNode->textContent;
            }

            if (trim($text) !== '') {
                $paragraphs[] = $text;
            }
        }

        return $paragraphs;
    }

    public function downloadRefereeAttachment(Application $application, ApplicationReferee $referee)
    {
        [$path, $name] = $this->refereeAttachmentDetails($application, $referee);

        return Storage::disk('public')->download($path, $name);
    }

    public function hodDecision(Request $request, Application $application)
    {
        $data = $request->validate([
            'decision' => 'required|in:recommended,rejected',
            'comment'  => 'nullable|string',
        ]);

        $application->update([
            'hod_decision'    => $data['decision'],
            'hod_comment'     => $data['comment'] ?? null,
            'hod_id'          => auth()->id(),
            'hod_reviewed_at' => now(),
            'status'          => $data['decision'] === 'recommended' ? 'under_review' : 'rejected',
        ]);

        if ($data['decision'] === 'recommended') {
            User::role('director_bps')->get()->each(
                fn ($d) => $d->notify(new ApplicationStatusNotification($application, 'director_review_required'))
            );
        } else {
            Notification::route('mail', [$application->email => $application->full_name])
                ->notify(new ApplicationStatusNotification($application, 'rejected', [
                    'reason' => $data['comment'] ?? null,
                ]));
        }

        return back()->with('success', 'Recommendation recorded.');
    }

    public function directorDecision(Request $request, Application $application)
    {
        $data = $request->validate([
            'decision' => 'required|in:admitted,rejected',
            'comment'  => 'nullable|string',
        ]);

        $application->update([
            'director_decision'    => $data['decision'],
            'director_comment'     => $data['comment'] ?? null,
            'director_id'          => auth()->id(),
            'director_reviewed_at' => now(),
            'status'               => $data['decision'] === 'admitted' ? 'offer_made' : 'rejected',
        ]);

        Notification::route('mail', [$application->email => $application->full_name])
            ->notify(new ApplicationStatusNotification(
                $application,
                $data['decision'] === 'admitted' ? 'offer_made' : 'rejected',
                $data['decision'] === 'admitted' ? [] : ['reason' => $data['comment'] ?? null]
            ));

        return back()->with('success', 'Admission decision recorded and applicant notified.');
    }
}

