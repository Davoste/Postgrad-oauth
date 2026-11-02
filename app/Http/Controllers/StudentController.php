<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:student']);
    }

    public function dashboard()
    {
        $user    = Auth::user();
        $student = $user->student;
        $thesis  = optional($student)->thesis;

        if (!$thesis) {
            $application = Application::where('user_id', $user->id)->latest()->first();

            return view('student.dashboard', [
                'thesis'      => null,
                'student'     => $student,
                'admitted'    => (bool) $student,
                'application' => $application,
            ]);
        }

        $thesis->load(['supervisor.user', 'meetings', 'progressReports.supervisor.user', 'milestones']);

        $pendingMeetingSign = $thesis->meetings()
            ->where('signed_student', false)
            ->where('signed_supervisor', true)
            ->latest('meeting_date')
            ->first();

        $pendingReportAck = $thesis->progressReports()
            ->whereNull('student_acknowledged_at')
            ->latest('submitted_at')
            ->first();

        $meetingsThisMonth = $thesis->getMeetingsThisMonth();

        return view('student.dashboard', compact(
            'student', 'thesis', 'pendingMeetingSign',
            'pendingReportAck', 'meetingsThisMonth'
        ) + ['admitted' => true]);
    }
}
