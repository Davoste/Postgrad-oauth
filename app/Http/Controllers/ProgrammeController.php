<?php

namespace App\Http\Controllers;

use App\Models\Programme;

class ProgrammeController extends Controller
{
    /** Public catalogue: all active programmes, grouped by school. */
    public function index()
    {
        $programmes = Programme::active()
            ->with('department.school', 'requirements')
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($p) => $p->department->school->name ?? 'Other');

        return view('programmes.index', compact('programmes'));
    }
}
