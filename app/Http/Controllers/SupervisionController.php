<?php

namespace App\Http\Controllers;

use App\Mail\MeetingLogged;
use App\Models\SupervisionMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SupervisionController extends Controller
{
    /** Supervisor: List of meetings for their students. */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('supervisor') && $user->supervisor) {
            $meetings = SupervisionMeeting::where('supervisor_id', $user->supervisor->id)
                ->with(['student.user', 'thesis'])
                ->orderBy('meeting_date', 'desc')
                ->paginate(20);
        } elseif ($user->hasRole('student') && $user->student) {
            $meetings = SupervisionMeeting::where('student_id', $user->student->id)
                ->with(['supervisor.user', 'thesis'])
                ->orderBy('meeting_date', 'desc')
                ->paginate(20);
        } else {
            // admin / other roles: all meetings
            $meetings = SupervisionMeeting::with(['student.user', 'supervisor.user', 'thesis'])
                ->orderBy('meeting_date', 'desc')
                ->paginate(20);
        }

        return view('supervision.meetings-list', compact('meetings'));
    }

    /** Supervisor: Show log-meeting form. */
    public function create()
    {
        $user = Auth::user();
        if (!$user->hasRole('supervisor') || !$user->supervisor) {
            abort(403, 'Only supervisors can log meetings.');
        }

        $students = $user->supervisor->students()->with('user')->get();
        return view('supervision.log-meeting', compact('students'));
    }

    /** Supervisor: Store a new meeting (supervisor auto-signed). */
    public function store(Request $request)
    {
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'meeting_date' => 'required|date',
            'agenda'       => 'required|string|max:2000',
            'minutes'      => 'required|string|max:5000',
        ]);

        $user       = Auth::user();
        $supervisor = $user->supervisor;

        if (!$supervisor) abort(403, 'Only supervisors can log meetings.');

        // Find the thesis linking this supervisor and student
        $thesis = \App\Models\Thesis::where('supervisor_id', $supervisor->id)
            ->where('student_id', $request->student_id)
            ->first();

        $meeting = SupervisionMeeting::create([
            'thesis_id'           => optional($thesis)->id,
            'student_id'          => $request->student_id,
            'supervisor_id'       => $supervisor->id,
            'meeting_date'        => $request->meeting_date,
            'agenda'              => $request->agenda,
            'minutes'             => $request->minutes,
            'signed_supervisor'   => true,
            'supervisor_sign_ip'  => $request->ip(),
        ]);

        // Notify student to sign
        try {
            $student = $meeting->student;
            if (optional(optional($student)->user)->email) {
                Mail::to($student->user->email)->send(new MeetingLogged($meeting));
            }
        } catch (\Exception $e) {
            \Log::warning('Meeting log email failed: ' . $e->getMessage());
        }

        activity()->performedOn($meeting)->log('Supervision meeting logged by supervisor');

        return redirect()->route('supervision.meetings')
            ->with('success', 'Meeting logged. Student has been notified to sign off.');
    }

    /** Student: Sign off a meeting. */
    public function signoff(SupervisionMeeting $meeting)
    {
        $this->authorize('sign', $meeting);

        $meeting->update([
            'signed_student'  => true,
            'student_sign_ip' => request()->ip(),
        ]);

        activity()->performedOn($meeting)->log('Supervision meeting acknowledged by student');

        return back()->with('success', 'Meeting signed off successfully.');
    }
}
