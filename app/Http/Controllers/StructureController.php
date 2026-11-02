<?php

namespace App\Http\Controllers;

use App\Models\{Department, School, User};
use Illuminate\Http\Request;

class StructureController extends Controller
{
    public function index()
    {
        $schools = School::with(['departments' => fn ($q) => $q->orderBy('name'), 'departments.hod', 'dean'])
            ->orderBy('name')->get();
        $hods  = User::role('hod')->orderBy('name')->get(['id', 'name']);
        $deans = User::role('dean')->orderBy('name')->get(['id', 'name']);

        return view('admin.structure', compact('schools', 'hods', 'deans'));
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $data = $request->validate(['hod_id' => 'nullable|exists:users,id']);
        $department->update(['hod_id' => $data['hod_id'] ?? null]);

        return back()->with('success', "Head of Department updated for {$department->name}.");
    }

    public function updateSchool(Request $request, School $school)
    {
        $data = $request->validate(['dean_id' => 'nullable|exists:users,id']);
        $school->update(['dean_id' => $data['dean_id'] ?? null]);

        return back()->with('success', "Dean updated for {$school->name}.");
    }
}
