<?php

namespace App\Http\Controllers;

use App\Models\ThesisSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function download(ThesisSubmission $submission, int $index)
    {
        $user   = Auth::user();
        $thesis = $submission->thesis;

        $isStudent    = $user->hasRole('student') && optional($user->student)->id === $thesis->student_id;
        $isSupervisor = $user->hasRole('supervisor') && optional($user->supervisor)->id === $thesis->supervisor_id;
        $isStaff      = $user->hasAnyRole(['admin', 'hod', 'dean', 'ethics_reviewer']);

        abort_unless($isStudent || $isSupervisor || $isStaff, 403);

        $documents = $submission->documents;
        abort_unless(is_array($documents) && isset($documents[$index]), 404);

        $doc  = $documents[$index];
        $path = Storage::disk('public')->path($doc['path']);
        abort_unless(file_exists($path), 404);

        return response()->download($path, $doc['original_name']);
    }
}
