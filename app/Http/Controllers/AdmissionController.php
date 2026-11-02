<?php

namespace App\Http\Controllers;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdmissionController extends Controller {
    public function store(Request $request) {
        $validated = $request->validate([
            'program' => 'required|string|max:255',
            'documents.*' => 'required|file|mimes:pdf,docx|max:10240',
        ]);

        $app = Application::create([
            'user_id' => Auth::id(),
            'program' => $validated['program'],
            'documents' => collect($request->file('documents'))->map->store('applications')->toJson()
        ]);

        return redirect()->back()->with('success', 'Application submitted.');
    }

    public function review(Application $application, Request $request) {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'score' => 'nullable|numeric|min:0|max:100',
            'decision_notes' => 'nullable|string'
        ]);

        $application->update($validated);
        if ($validated['status'] === 'approved') {
            // Auto-create student & thesis record
            $student = \App\Models\Student::create([
                'user_id' => $application->user_id,
                'reg_no' => 'PG-'.date('Y').'-'.str_pad(\App\Models\Student::max('id') + 1, 4, '0', STR_PAD_LEFT),
                'program' => $application->program,
                'admission_year' => now()->year
            ]);
            \App\Models\Thesis::create(['student_id' => $student->id, 'title' => 'Untitled Thesis']);
            $student->user->assignRole('student');
        }

        return back()->with('success', 'Decision recorded.');
    }
}