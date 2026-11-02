<?php

namespace App\Http\Controllers;

use App\Models\ProgressReport;
use App\Models\Thesis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressReportController extends Controller
{
    /** Supervisor: list all progress reports for their students. */
    public function supervisorIndex()
    {
        $supervisor = Auth::user()->supervisor;
        abort_unless($supervisor, 403);

        $reports = $supervisor->progressReports()
            ->with(['thesis.student.user'])
            ->orderBy('period_start', 'desc')
            ->paginate(20);

        $theses = $supervisor->theses()
            ->with('student.user')
            ->withCount('progressReports')
            ->get();

        return view('progress-reports.supervisor-index', compact('reports', 'theses'));
    }

    /** List all progress reports for a thesis. */
    public function index(Thesis $thesis)
    {
        $this->authorizeAccess($thesis);

        $reports = $thesis->progressReports()
            ->with('supervisor.user')
            ->orderBy('period_start', 'desc')
            ->get();

        return view('progress-reports.index', compact('thesis', 'reports'));
    }

    /** Supervisor: show form to create a new report. */
    public function create(Thesis $thesis)
    {
        $this->authorize('submitProgressReport', $thesis);

        return view('progress-reports.create', compact('thesis'));
    }

    /** Supervisor: store new progress report. */
    public function store(Request $request, Thesis $thesis)
    {
        $this->authorize('submitProgressReport', $thesis);

        $request->validate([
            'report_type'             => 'required|in:quarterly,biannual',
            'period_start'            => 'required|date',
            'period_end'              => 'required|date|after:period_start',
            'content'                 => 'required|string|min:100',
            'student_progress_rating' => 'required|integer|min:1|max:5',
            'challenges'              => 'nullable|string',
            'recommendations'         => 'nullable|string',
        ]);

        $supervisor = Auth::user()->supervisor;
        if (!$supervisor) abort(403);

        ProgressReport::create([
            'thesis_id'               => $thesis->id,
            'supervisor_id'           => $supervisor->id,
            'report_type'             => $request->report_type,
            'period_start'            => $request->period_start,
            'period_end'              => $request->period_end,
            'content'                 => $request->content,
            'student_progress_rating' => $request->student_progress_rating,
            'challenges'              => $request->challenges,
            'recommendations'         => $request->recommendations,
            'submitted_at'            => now(),
        ]);

        return redirect()->route('progress-reports.index', $thesis)
            ->with('success', 'Progress report submitted. The student has been notified to acknowledge it.');
    }

    /** View a single progress report. */
    public function show(ProgressReport $report)
    {
        $this->authorizeAccess($report->thesis);
        $report->load(['thesis.student.user', 'supervisor.user']);
        return view('progress-reports.show', compact('report'));
    }

    /** Student: acknowledge a progress report. */
    public function acknowledge(ProgressReport $report)
    {
        $student = Auth::user()->student;
        abort_unless($student && $report->thesis->student_id === $student->id, 403);
        abort_if($report->isAcknowledged(), 400, 'Already acknowledged.');

        $report->update(['student_acknowledged_at' => now()]);

        return back()->with('success', 'Report acknowledged.');
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function authorizeAccess(Thesis $thesis): void
    {
        $user = Auth::user();

        $isStudent    = $user->hasRole('student')    && optional($user->student)->id === $thesis->student_id;
        $isSupervisor = $user->hasRole('supervisor') && optional($user->supervisor)->id === $thesis->supervisor_id;
        $isAdmin      = $user->hasRole('admin') || $user->hasRole('hod') || $user->hasRole('dean');

        abort_unless($isStudent || $isSupervisor || $isAdmin, 403);
    }
}
