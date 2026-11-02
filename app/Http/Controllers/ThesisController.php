<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackGiven;
use App\Mail\SubmissionReceived;
use App\Mail\SupervisorAllocated;
use App\Models\Supervisor;
use App\Models\Thesis;
use App\Models\User;
use App\Models\ThesisFeedback;
use App\Models\ThesisMilestone;
use App\Models\ThesisReview;
use App\Models\ThesisSubmission;
use App\Models\SupervisionMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ThesisController extends Controller
{
    // ── Student: Thesis Dashboard ────────────────────────────────────────────

    public function dashboard()
    {
        $user   = Auth::user();
        $thesis = optional($user->student)->thesis;

        if (!$thesis) {
            return view('thesis.no-thesis');
        }

        $thesis->load(['submissions', 'feedbacks.reviewer', 'milestones', 'meetings.supervisor.user']);

        return view('thesis.dashboard', [
            'thesis'            => $thesis,
            'currentSubmission' => $thesis->getLatestSubmissionForStage($thesis->current_stage),
            'latestFeedback'    => $thesis->getLatestFeedbackForStage($thesis->current_stage),
            'pendingMilestones' => $thesis->getPendingMilestones(),
            'overdueMilestones' => $thesis->getOverdueMilestones(),
            'recentMeetings'    => $thesis->meetings()->with('supervisor.user')->latest()->take(5)->get(),
            'stageProgress'     => $this->calculateStageProgress($thesis),
        ]);
    }

    // ── Student: Submissions ─────────────────────────────────────────────────

    public function submitConcept(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'documents.*'    => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $student = Auth::user()->student;
        if (!$student) return back()->with('error', 'Student profile not found.');

        $thesis = $student->thesis;
        if (!$thesis) {
            $thesis = Thesis::create([
                'student_id' => $student->id,
                'title'      => $request->title,
                'state'      => 'concept_development',
            ]);
        } else {
            $thesis->update(['title' => $request->title]);
        }

        $documents = $this->storeFiles($request, 'thesis-documents/concept');

        $submission = ThesisSubmission::create([
            'thesis_id'       => $thesis->id,
            'stage'           => 'concept_development',
            'submission_type' => 'concept',
            'title'           => $request->title,
            'description'     => $request->description,
            'documents'       => $documents,
            'status'          => 'submitted',
            'submitted_at'    => now(),
        ]);

        $this->notifyReviewersOfSubmission($thesis, $submission);

        return back()->with('success', 'Concept submitted successfully. Your supervisor will review it shortly.');
    }

    public function submitProposal(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $thesis = Auth::user()->student->thesis;
        if (!$thesis) return back()->with('error', 'No active thesis found.');

        $documents  = $this->storeFiles($request, 'thesis-documents/proposal');
        $submission = ThesisSubmission::create([
            'thesis_id'       => $thesis->id,
            'stage'           => 'proposal_development',
            'submission_type' => 'proposal',
            'title'           => $request->title,
            'description'     => $request->description,
            'documents'       => $documents,
            'status'          => 'submitted',
            'submitted_at'    => now(),
        ]);

        $this->notifyReviewersOfSubmission($thesis, $submission);

        return back()->with('success', 'Proposal submitted. Your supervisor will review it.');
    }

    public function submitEthicsForm(Request $request)
    {
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'documents.*' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $thesis = Auth::user()->student->thesis;
        if (!$thesis) return back()->with('error', 'No active thesis found.');

        $documents  = $this->storeFiles($request, 'thesis-documents/ethics');
        $submission = ThesisSubmission::create([
            'thesis_id'       => $thesis->id,
            'stage'           => 'ethical_approval',
            'submission_type' => 'ethics_form',
            'title'           => $request->title ?? 'Ethics Application',
            'description'     => $request->description,
            'documents'       => $documents,
            'status'          => 'submitted',
            'submitted_at'    => now(),
        ]);

        // Notify ethics reviewer(s) and supervisor
        $this->notifyReviewersOfSubmission($thesis, $submission, true);

        return back()->with('success', 'Ethics form submitted. It will be reviewed by the ethics committee.');
    }

    public function submitIntentToSubmit(Request $request)
    {
        $request->validate([
            'intent_to_submit_date' => 'required|date|after:today',
        ]);

        $thesis = Auth::user()->student->thesis;
        if (!$thesis) return back()->with('error', 'No active thesis found.');

        $thesis->update(['intent_to_submit_date' => $request->intent_to_submit_date]);

        $submission = ThesisSubmission::create([
            'thesis_id'       => $thesis->id,
            'stage'           => 'intent_to_submit',
            'submission_type' => 'intent',
            'title'           => 'Intent to Submit — expected ' . $request->intent_to_submit_date,
            'description'     => 'Student declared intent to submit thesis by ' . $request->intent_to_submit_date,
            'documents'       => [],
            'status'          => 'submitted',
            'submitted_at'    => now(),
        ]);

        activity()->performedOn($thesis)->log('Student declared intent to submit');

        $this->notifyReviewersOfSubmission($thesis, $submission);

        return back()->with('success', 'Intent to submit recorded. Your submission date has been set.');
    }

    public function submitThesisDocument(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'documents'   => 'required|array|min:1',
            'documents.*' => 'file|mimes:pdf,doc,docx|max:51200',
        ]);

        $thesis = Auth::user()->student->thesis;
        if (!$thesis) return back()->with('error', 'No active thesis found.');

        $documents  = $this->storeFiles($request, 'thesis-documents/final');
        $submission = ThesisSubmission::create([
            'thesis_id'       => $thesis->id,
            'stage'           => $thesis->current_stage,
            'submission_type' => 'thesis_document',
            'title'           => $request->title,
            'documents'       => $documents,
            'status'          => 'submitted',
            'submitted_at'    => now(),
        ]);

        $this->notifyReviewersOfSubmission($thesis, $submission);

        return back()->with('success', 'Thesis document submitted successfully.');
    }

    // ── Student: View Pages ──────────────────────────────────────────────────

    public function viewSubmissions()
    {
        $thesis = optional(Auth::user()->student)->thesis;
        if (!$thesis) return redirect()->route('thesis.dashboard');

        $submissions = $thesis->submissions()
            ->with(['feedbacks.reviewer', 'reviews.reviewer'])
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->groupBy('stage');

        return view('thesis.submissions', compact('submissions', 'thesis'));
    }

    public function viewFeedback()
    {
        $thesis = optional(Auth::user()->student)->thesis;
        if (!$thesis) return redirect()->route('thesis.dashboard');

        $feedbacks = $thesis->feedbacks()
            ->with('reviewer')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('stage');

        return view('thesis.feedback', compact('feedbacks', 'thesis'));
    }

    public function viewMilestones()
    {
        $thesis = optional(Auth::user()->student)->thesis;
        if (!$thesis) return redirect()->route('thesis.dashboard');

        $milestones = $thesis->milestones()
            ->orderBy('target_date')
            ->get()
            ->groupBy('stage');

        return view('thesis.milestones', compact('milestones', 'thesis'));
    }

    // ── Student: Request a Meeting ───────────────────────────────────────────

    public function requestMeeting(Request $request)
    {
        $request->validate([
            'meeting_date' => 'required|date|after:today',
            'agenda'       => 'required|string|max:1000',
        ]);

        $student = Auth::user()->student;
        $thesis  = optional($student)->thesis;

        if (!$thesis || !$thesis->supervisor_id) {
            return back()->with('error', 'No supervisor assigned yet. Contact the admin.');
        }

        SupervisionMeeting::create([
            'thesis_id'     => $thesis->id,
            'student_id'    => $student->id,
            'supervisor_id' => $thesis->supervisor_id,
            'meeting_date'  => $request->meeting_date,
            'agenda'        => $request->agenda,
            'minutes'       => null,
            'signed_student'    => false,
            'signed_supervisor' => false,
        ]);

        return back()->with('success', 'Meeting request submitted. Your supervisor has been notified.');
    }

    // ── Admin/Supervisor: Review Submissions ─────────────────────────────────

    public function reviewSubmissions()
    {
        $user = Auth::user();

        $query = ThesisSubmission::with(['thesis.student.user', 'thesis.supervisor.user'])
            ->whereIn('status', ['submitted', 'under_review'])
            ->orderBy('submitted_at');

        // Supervisors only see their own students' submissions
        if ($user->hasRole('supervisor') && $user->supervisor) {
            $query->whereHas('thesis', fn($q) =>
                $q->where('supervisor_id', $user->supervisor->id)
            );
        }

        // Ethics reviewers see only ethics submissions
        if ($user->hasRole('ethics_reviewer')) {
            $query->where('stage', 'ethical_approval');
        }

        $pendingSubmissions = $query->get();

        return view('admin.thesis-reviews', compact('pendingSubmissions'));
    }

    public function submitReview(Request $request, ThesisSubmission $submission)
    {
        $this->authorize('review', $submission->thesis);

        $request->validate([
            'decision'         => 'required|in:approved,rejected,revision_required',
            'comments'         => 'required|string',
            'rating'           => 'nullable|integer|min:1|max:5',
            'strengths'        => 'nullable|string',
            'weaknesses'       => 'nullable|string',
            'recommendations'  => 'required|string',
            'requires_meeting' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $submission) {
            $reviewType = Auth::user()->hasRole('admin')
                ? 'admin_review'
                : (Auth::user()->hasRole('ethics_reviewer') ? 'ethics_review' : 'supervisor_review');

            ThesisReview::create([
                'thesis_id'       => $submission->thesis_id,
                'submission_id'   => $submission->id,
                'reviewer_id'     => Auth::id(),
                'review_type'     => $reviewType,
                'stage'           => $submission->stage,
                'rating'          => $request->rating,
                'strengths'       => $request->strengths,
                'weaknesses'      => $request->weaknesses,
                'recommendations' => $request->recommendations,
                'decision'        => $request->decision,
                'requires_meeting'=> $request->boolean('requires_meeting'),
                'reviewed_at'     => now(),
            ]);

            ThesisFeedback::create([
                'thesis_id'    => $submission->thesis_id,
                'submission_id'=> $submission->id,
                'reviewer_id'  => Auth::id(),
                'stage'        => $submission->stage,
                'comments'     => $request->comments,
                'decision'     => $request->decision,
                'is_final'     => true,
            ]);

            $submission->update([
                'status'      => $request->decision,
                'reviewed_at' => now(),
            ]);

            if ($request->decision === 'approved') {
                $this->handleApprovalTransition($submission->thesis, $submission->stage);
            }
        });

        // Email student
        try {
            $student = $submission->thesis->student;
            if (optional(optional($student)->user)->email) {
                Mail::to($student->user->email)->send(
                    new FeedbackGiven($submission->thesis, $submission, $request->decision, $request->comments, Auth::user()->name)
                );
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to send feedback email: ' . $e->getMessage());
        }

        return back()->with('success', 'Review submitted successfully.');
    }

    // ── Admin: Allocate Supervisor ───────────────────────────────────────────

    public function allocateSupervisorForm(Thesis $thesis)
    {
        $this->authorize('allocateSupervisor', $thesis);
        $supervisors = Supervisor::with('user')->get();
        return view('admin.allocate-supervisor', compact('thesis', 'supervisors'));
    }

    public function allocateSupervisor(Request $request, Thesis $thesis)
    {
        $this->authorize('allocateSupervisor', $thesis);

        $request->validate([
            'supervisor_id' => 'required|exists:supervisors,id',
        ]);

        $thesis->update(['supervisor_id' => $request->supervisor_id]);

        $this->createInitialMilestones($thesis);

        // Email both parties
        try {
            $supervisor = Supervisor::find($request->supervisor_id);
            Mail::to($thesis->student->user->email)
                ->send(new SupervisorAllocated($thesis, $supervisor, 'student'));
            Mail::to($supervisor->user->email)
                ->send(new SupervisorAllocated($thesis, $supervisor, 'supervisor'));
        } catch (\Exception $e) {
            \Log::warning('Supervisor allocation email failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.theses')->with('success', 'Supervisor allocated successfully.');
    }

    // ── Admin: Transition ────────────────────────────────────────────────────

    public function transition(Request $request, Thesis $thesis)
    {
        $this->authorize('update', $thesis);

        $request->validate([
            'transition' => 'required|string',
            'notes'      => 'nullable|string',
        ]);

        if ($thesis->transitionTo($request->transition)) {
            activity()
                ->performedOn($thesis)
                ->withProperties(['transition' => $request->transition, 'notes' => $request->notes])
                ->log('Thesis stage transitioned to ' . $request->transition);

            return back()->with('success', 'Thesis stage updated to: ' . Thesis::STAGE_LABELS[$request->transition] ?? $request->transition);
        }

        return back()->with('error', 'Invalid or unavailable transition.');
    }

    // ── Private Helpers ──────────────────────────────────────────────────────

    private function storeFiles(Request $request, string $folder): array
    {
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $storedName = $file->hashName();
                $path       = $file->storeAs($folder, $storedName, 'public');
                $documents[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path'          => $path,
                    'size'          => $file->getSize(),
                    'mime'          => $file->getMimeType(),
                ];
            }
        }
        return $documents;
    }

    private function handleApprovalTransition(Thesis $thesis, string $stage): void
    {
        $transitions = [
            'concept_development'  => 'proposal_development',
            'proposal_development' => 'ethical_approval',
            'ethical_approval'     => 'data_collection',
            'data_collection'      => 'writing_thesis',
            'writing_thesis'       => 'intent_to_submit',
            'intent_to_submit'     => 'submitted',
            'submitted'            => 'examination',
            'examination'          => 'graduated',
        ];

        if (isset($transitions[$stage])) {
            $thesis->transitionTo($transitions[$stage]);
        }
    }

    private function notifyReviewersOfSubmission(Thesis $thesis, ThesisSubmission $submission, bool $notifyEthics = false): void
    {
        try {
            if (optional(optional($thesis->supervisor)->user)->email) {
                Mail::to($thesis->supervisor->user->email)
                    ->send(new SubmissionReceived($thesis, $submission, 'supervisor'));
            }
        } catch (\Exception $e) {
            \Log::warning('Submission notification (supervisor) failed: ' . $e->getMessage());
        }

        if ($notifyEthics) {
            try {
                $ethicsReviewers = User::role('ethics_reviewer')->get();
                foreach ($ethicsReviewers as $reviewer) {
                    Mail::to($reviewer->email)
                        ->send(new SubmissionReceived($thesis, $submission, 'ethics_reviewer'));
                }
            } catch (\Exception $e) {
                \Log::warning('Submission notification (ethics) failed: ' . $e->getMessage());
            }
        }
    }

    private function createInitialMilestones(Thesis $thesis): void
    {
        $milestones = [
            ['stage' => 'concept_development',  'milestone_name' => 'Concept Development',  'description' => 'Develop and submit initial thesis concept',          'weeks' => 4],
            ['stage' => 'proposal_development', 'milestone_name' => 'Proposal Development', 'description' => 'Complete and submit thesis proposal',               'weeks' => 12],
            ['stage' => 'ethical_approval',     'milestone_name' => 'Ethics Clearance',      'description' => 'Obtain ethical clearance for research',             'weeks' => 20],
            ['stage' => 'data_collection',      'milestone_name' => 'Data Collection',       'description' => 'Complete data collection phase',                   'weeks' => 32],
            ['stage' => 'writing_thesis',       'milestone_name' => 'Thesis Writing',        'description' => 'Complete thesis writing and editing',               'weeks' => 44],
            ['stage' => 'intent_to_submit',     'milestone_name' => 'Intent to Submit',      'description' => 'Submit intent to submit thesis',                   'weeks' => 48],
        ];

        foreach ($milestones as $m) {
            ThesisMilestone::firstOrCreate(
                ['thesis_id' => $thesis->id, 'stage' => $m['stage']],
                [
                    'milestone_name' => $m['milestone_name'],
                    'description'    => $m['description'],
                    'target_date'    => now()->addWeeks($m['weeks']),
                    'status'         => 'pending',
                ]
            );
        }
    }

    private function calculateStageProgress(Thesis $thesis): array
    {
        $progress    = [];
        $currentIdx  = $thesis->stage_index;

        foreach (Thesis::STAGE_ORDER as $idx => $stage) {
            $submission = $thesis->getLatestSubmissionForStage($stage);
            $feedback   = $thesis->getLatestFeedbackForStage($stage);

            if ($idx < $currentIdx) {
                $status = 'completed';
            } elseif ($idx === $currentIdx) {
                $status = 'current';
            } else {
                $status = 'pending';
            }

            // Override: if current stage has a reviewed submission
            if ($idx === $currentIdx && $submission && $feedback) {
                $status = $feedback->decision === 'approved' ? 'completed' : 'current';
            }

            $progress[$stage] = [
                'name'        => Thesis::STAGE_LABELS[$stage],
                'description' => Thesis::STAGE_DESCRIPTIONS[$stage],
                'status'      => $status,
                'submission'  => $submission,
                'feedback'    => $feedback,
            ];
        }

        return $progress;
    }
}
