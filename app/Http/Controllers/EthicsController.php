<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackGiven;
use App\Models\Thesis;
use App\Models\ThesisFeedback;
use App\Models\ThesisReview;
use App\Models\ThesisSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EthicsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:ethics_reviewer|admin']);
    }

    /** List ethics submissions — pending and reviewed. */
    public function pending()
    {
        $base = ThesisSubmission::with(['thesis.student.user', 'thesis.supervisor.user'])
            ->where('stage', 'ethical_approval');

        $pending = (clone $base)
            ->whereIn('status', ['submitted', 'under_review'])
            ->orderBy('submitted_at')
            ->get();

        $reviewed = (clone $base)
            ->whereNotIn('status', ['submitted', 'under_review'])
            ->orderByDesc('reviewed_at')
            ->get();

        return view('ethics.pending', compact('pending', 'reviewed'));
    }

    /** Show a single ethics submission. */
    public function show(ThesisSubmission $submission)
    {
        abort_unless($submission->stage === 'ethical_approval', 404);
        $submission->load(['thesis.student.user', 'thesis.supervisor.user', 'reviews.reviewer', 'feedbacks.reviewer']);
        return view('ethics.review', compact('submission'));
    }

    /** Submit ethics review decision. */
    public function review(Request $request, ThesisSubmission $submission)
    {
        abort_unless($submission->stage === 'ethical_approval', 404);

        $request->validate([
            'decision'        => 'required|in:approved,rejected,revision_required',
            'comments'        => 'required|string',
            'recommendations' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $submission) {
            ThesisReview::create([
                'thesis_id'       => $submission->thesis_id,
                'submission_id'   => $submission->id,
                'reviewer_id'     => Auth::id(),
                'review_type'     => 'ethics_review',
                'stage'           => 'ethical_approval',
                'recommendations' => $request->recommendations,
                'decision'        => $request->decision,
                'reviewed_at'     => now(),
            ]);

            ThesisFeedback::create([
                'thesis_id'     => $submission->thesis_id,
                'submission_id' => $submission->id,
                'reviewer_id'   => Auth::id(),
                'stage'         => 'ethical_approval',
                'comments'      => $request->comments,
                'decision'      => $request->decision,
                'is_final'      => true,
            ]);

            $submission->update([
                'status'      => $request->decision,
                'reviewed_at' => now(),
            ]);

            // Auto-transition if approved
            if ($request->decision === 'approved') {
                $submission->thesis->transitionTo('data_collection');
            }
        });

        // Notify student
        try {
            $student = $submission->thesis->student;
            if (optional(optional($student)->user)->email) {
                Mail::to($student->user->email)
                    ->send(new FeedbackGiven($submission->thesis, $submission, $request->decision, $request->comments, Auth::user()->name));
            }
        } catch (\Exception $e) {
            \Log::warning('Ethics review email failed: ' . $e->getMessage());
        }

        return redirect()->route('ethics.pending')
            ->with('success', 'Ethics review submitted successfully.');
    }
}
