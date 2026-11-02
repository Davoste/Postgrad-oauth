<?php

namespace App\Http\Controllers;

use App\Models\ThesisSubmission;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:supervisor']);
    }

    public function dashboard()
    {
        $user       = Auth::user();
        $supervisor = $user->supervisor;

        if (!$supervisor) abort(403);

        $theses = $supervisor->theses()
            ->with(['student.user', 'meetings', 'milestones', 'progressReports'])
            ->whereNotIn('state', ['graduated'])
            ->get();

        // Annotate each thesis with accountability metrics
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        foreach ($theses as $thesis) {
            $thesis->meetings_this_month = $thesis->meetings()
                ->whereYear('meeting_date', $currentYear)
                ->whereMonth('meeting_date', $currentMonth)
                ->count();

            $thesis->last_meeting = $thesis->meetings()->latest('meeting_date')->first();

            $thesis->pending_submissions = ThesisSubmission::where('thesis_id', $thesis->id)
                ->whereIn('status', ['submitted', 'under_review'])
                ->count();
        }

        $pendingReviews = ThesisSubmission::whereHas('thesis', fn($q) =>
                $q->where('supervisor_id', $supervisor->id)
            )
            ->whereIn('status', ['submitted', 'under_review'])
            ->with(['thesis.student.user'])
            ->orderBy('submitted_at')
            ->get();

        return view('supervisor.dashboard', compact('supervisor', 'theses', 'pendingReviews'));
    }
}
