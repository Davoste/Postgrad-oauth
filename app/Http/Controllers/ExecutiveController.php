<?php

namespace App\Http\Controllers;

use App\Models\{Application, FeeInvoice, Programme, School, Student};

class ExecutiveController extends Controller
{
    /** Institution-wide, read-only oversight for top management (VC / DVC / Director). */
    public function index()
    {
        $appsByStatus = Application::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');
        $totalApps    = (int) $appsByStatus->sum();

        $funnel = [
            'Applications'      => $totalApps,
            'Recommended/onward'=> (int) Application::whereIn('status', ['under_review', 'offer_made', 'accepted'])->count(),
            'Offers made'       => (int) Application::whereIn('status', ['offer_made', 'accepted'])->count(),
            'Enrolled'          => (int) ($appsByStatus['accepted'] ?? 0),
        ];

        $studentsByStatus = Student::selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');
        $progByLevel      = Programme::active()->selectRaw('level, count(*) c')->groupBy('level')->pluck('c', 'level');

        $studentsBySchool = Student::query()
            ->join('programmes', 'students.programme_id', '=', 'programmes.id')
            ->join('departments', 'programmes.department_id', '=', 'departments.id')
            ->join('schools', 'departments.school_id', '=', 'schools.id')
            ->selectRaw('schools.name as school, count(*) as total')
            ->groupBy('schools.name')->orderByDesc('total')->pluck('total', 'school');

        $finance = [
            'billed'      => (float) FeeInvoice::sum('amount'),
            'collected'   => (float) FeeInvoice::sum('amount_paid'),
            'outstanding' => (float) FeeInvoice::whereIn('status', ['unpaid', 'partial'])->get()->sum(fn ($i) => $i->balance),
        ];

        $kpis = [
            'applications' => $totalApps,
            'students'     => (int) Student::count(),
            'programmes'   => (int) Programme::active()->count(),
            'schools'      => (int) School::count(),
        ];

        return view('executive.dashboard', compact(
            'kpis', 'appsByStatus', 'funnel', 'studentsByStatus', 'progByLevel', 'studentsBySchool', 'finance'
        ));
    }
}
