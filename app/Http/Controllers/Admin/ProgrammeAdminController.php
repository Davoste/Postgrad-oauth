<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Programme;
use Illuminate\Http\Request;

class ProgrammeAdminController extends Controller
{
    public function index()
    {
        $programmes = Programme::with('department.school')
            ->orderBy('name')->paginate(25);
        return view('admin.programmes.index', compact('programmes'));
    }

    public function create()
    {
        return view('admin.programmes.form', [
            'programme'   => new Programme(['level' => 'masters', 'duration_years' => 2, 'is_active' => true, 'applications_open' => true]),
            'departments' => $this->departments(),
        ]);
    }

    public function store(Request $request)
    {
        Programme::create($this->validated($request));
        return redirect()->route('admin.programmes.index')->with('success', 'Programme created.');
    }

    public function edit(Programme $programme)
    {
        return view('admin.programmes.form', [
            'programme'   => $programme,
            'departments' => $this->departments(),
        ]);
    }

    public function update(Request $request, Programme $programme)
    {
        $programme->update($this->validated($request, $programme->id));
        return redirect()->route('admin.programmes.index')->with('success', 'Programme updated.');
    }

    public function destroy(Programme $programme)
    {
        $programme->delete();
        return back()->with('success', 'Programme deleted.');
    }

    private function departments()
    {
        return Department::with('school')->orderBy('name')->get();
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'department_id'     => 'required|exists:departments,id',
            'name'              => 'required|string|max:180',
            'code'              => 'required|string|max:30|unique:programmes,code' . ($id ? ",{$id}" : ''),
            'level'             => 'required|in:pgd,masters,phd',
            'duration_years'    => 'required|integer|min:1|max:8',
            'tuition_per_year'  => 'required|numeric|min:0',
            'overview'          => 'nullable|string',
            'career_prospects'  => 'nullable|string',
            'modes'             => 'nullable|array',
            'modes.*'           => 'in:full_time,part_time,school_based,weekend_intensive',
        ]);

        $data['modes']             = $data['modes'] ?? [];
        $data['is_active']         = $request->boolean('is_active');
        $data['applications_open'] = $request->boolean('applications_open');

        return $data;
    }
}
