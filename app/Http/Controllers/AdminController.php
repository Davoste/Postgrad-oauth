<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Supervisor;
use App\Models\Thesis;
use App\Models\SupervisionMeeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // ── Dashboard ────────────────────────────────────────────────────────────

    public function dashboard()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_students'    => User::role('student')->count(),
            'total_supervisors' => User::role('supervisor')->count(),
            'total_theses'      => Thesis::count(),
            'active_theses'     => Thesis::whereNotIn('state', ['graduated'])->count(),
            'graduated'         => Thesis::where('state', 'graduated')->count(),
            'meetings_this_month' => SupervisionMeeting::whereYear('meeting_date', now()->year)
                                        ->whereMonth('meeting_date', now()->month)->count(),
        ];

        // Students per stage
        $byStage = [];
        foreach (Thesis::STAGE_ORDER as $stage) {
            $byStage[$stage] = [
                'label' => Thesis::STAGE_LABELS[$stage],
                'count' => Thesis::where('state', $stage)->count(),
            ];
        }

        // Recent activity log
        $recentActivity = \Spatie\Activitylog\Models\Activity::with('causer')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'byStage', 'recentActivity'));
    }

    // ── Monitor: All Students ─────────────────────────────────────────────────

    public function monitor(Request $request)
    {
        $query = Thesis::with(['student.user', 'supervisor.user', 'meetings'])
            ->when($request->stage, fn($q) => $q->where('state', $request->stage))
            ->when($request->supervisor_id, fn($q) => $q->where('supervisor_id', $request->supervisor_id));

        $theses      = $query->paginate(20)->appends($request->query());
        $supervisors = Supervisor::with('user')->orderBy('id')->get();
        $stages      = Thesis::STAGE_LABELS;

        // Accountability: annotate each thesis with meeting count this month
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        foreach ($theses as $thesis) {
            $thesis->meetings_this_month = $thesis->meetings()
                ->whereYear('meeting_date', $currentYear)
                ->whereMonth('meeting_date', $currentMonth)
                ->count();

            $thesis->overdue_milestones = $thesis->getOverdueMilestones()->count();
        }

        return view('admin.monitor', compact('theses', 'supervisors', 'stages'));
    }

    // ── User Management ───────────────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::with(['roles','student.thesis.supervisor.user']);

        if ($request->search) {
	    $term = $request -> search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
		  ->orWhere('email', 'LIKE', "%{$term}%")
                  ->orWhereHas('student', function ($sq) use ($term){
			$sq-> where('reg_no', 'LIKE', "%{$term}%");
			});
            });
        }

        if ($request->role) {
            $query->role($request->role);
        }

        $users = $query->paginate(15)->appends($request->query());
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function createUser()
    {
        $roles = Role::whereNotIn('name', ['admin'])->get();
        return view('admin.create-user', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string|exists:roles,name',
        ];

        if ($request->role === 'student') {
            $rules['reg_no']         = 'required|string|unique:students,reg_no';
            $rules['program']        = 'required|string|max:255';
            $rules['admission_year'] = 'required|integer|min:2000|max:' . (now()->year + 1);
        }

        if ($request->role === 'supervisor') {
            $rules['department']    = 'required|string|max:255';
            $rules['max_capacity']  = 'nullable|integer|min:1|max:20';
        }

        $request->validate($rules);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);
        $user->assignRole($request->role);

        if ($request->role === 'student') {
            Student::create([
                'user_id'        => $user->id,
                'reg_no'         => $request->reg_no,
                'program'        => $request->program,
                'admission_year' => $request->admission_year,
            ]);
        } elseif ($request->role === 'supervisor') {
            Supervisor::create([
                'user_id'      => $user->id,
                'department'   => $request->department,
                'max_capacity' => $request->max_capacity ?? 5,
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $roles      = Role::whereNotIn('name', ['admin'])->get();
        $userRole   = $user->roles->first();
        $student    = Student::where('user_id', $user->id)->first();
        $supervisor = Supervisor::where('user_id', $user->id)->first();

        return view('admin.edit-user', compact('user', 'roles', 'userRole', 'student', 'supervisor'));
    }

    public function updateUser(Request $request, User $user)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|string|exists:roles,name',
        ];

        $request->validate($rules);

        $user->update(['name' => $request->name, 'email' => $request->email]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles($request->role);

        if ($request->role === 'student') {
            $student = Student::firstOrNew(['user_id' => $user->id]);
            $student->fill([
                'reg_no'         => $request->reg_no         ?? $student->reg_no         ?? 'AUTO-' . $user->id,
                'program'        => $request->program        ?? $student->program        ?? 'N/A',
                'admission_year' => $request->admission_year ?? $student->admission_year ?? now()->year,
            ])->save();
        } elseif ($request->role === 'supervisor') {
            $supervisor = Supervisor::firstOrNew(['user_id' => $user->id]);
            $supervisor->fill([
                'department'   => $request->department   ?? $supervisor->department   ?? 'General',
                'max_capacity' => $request->max_capacity ?? $supervisor->max_capacity ?? 5,
            ])->save();
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    // ── Theses ────────────────────────────────────────────────────────────────

    public function theses(Request $request)
    {
        $query = Thesis::with(['student.user', 'supervisor.user'])
            ->when($request->stage, fn($q) => $q->where('state', $request->stage))
            ->when($request->supervisor_id, fn($q) => $q->where('supervisor_id', $request->supervisor_id));

        $theses      = $query->paginate(20)->appends($request->query());
        $supervisors = Supervisor::with('user')->orderBy('id')->get();
        $stages      = Thesis::STAGE_LABELS;

        return view('admin.theses', compact('theses', 'supervisors', 'stages'));
    }

    public function thesisDetail(Thesis $thesis)
    {
        $thesis->load([
            'student.user', 'supervisor.user',
            'submissions', 'feedbacks.reviewer',
            'milestones', 'meetings.supervisor.user',
            'progressReports.supervisor.user',
        ]);

        $supervisors   = Supervisor::with('user')->get();
        $stageProgress = $this->stageProgressForAdmin($thesis);

        return view('admin.thesis-detail', compact('thesis', 'supervisors', 'stageProgress'));
    }

    // ── Supervisions ─────────────────────────────────────────────────────────

    public function supervisions(Request $request)
    {
        $meetings = SupervisionMeeting::with(['student.user', 'supervisor.user', 'thesis'])
            ->when($request->supervisor_id, fn($q) => $q->where('supervisor_id', $request->supervisor_id))
            ->when($request->month, fn($q) => $q->whereMonth('meeting_date', $request->month))
            ->when($request->year,  fn($q) => $q->whereYear('meeting_date', $request->year))
            ->orderBy('meeting_date', 'desc')
            ->paginate(25)
            ->appends($request->query());

        $supervisors = Supervisor::with('user')->get();

        return view('admin.supervisions', compact('meetings', 'supervisors'));
    }

    // ── Reports ───────────────────────────────────────────────────────────────

    public function reports()
    {
        // Students per stage
        $byStage = collect(Thesis::STAGE_ORDER)->mapWithKeys(fn($stage) => [
            $stage => [
                'label' => Thesis::STAGE_LABELS[$stage],
                'count' => Thesis::where('state', $stage)->count(),
            ]
        ]);

        // Supervisor workload
        $supervisorLoad = Supervisor::with(['user', 'theses'])
            ->withCount(['theses as active_students' => fn($q) => $q->whereNotIn('state', ['graduated'])])
            ->get();

        // Meeting accountability last 3 months
        $meetingStats = SupervisionMeeting::selectRaw('YEAR(meeting_date) as yr, MONTH(meeting_date) as mo, COUNT(*) as total')
            ->where('meeting_date', '>=', now()->subMonths(3))
            ->groupBy('yr', 'mo')
            ->orderBy('yr')->orderBy('mo')
            ->get();

        // Overdue milestones
        $overdueMilestoneCount = \App\Models\ThesisMilestone::overdue()->count();

        $data = [
            'total_students'         => User::role('student')->count(),
            'total_supervisors'      => User::role('supervisor')->count(),
            'total_theses'           => Thesis::count(),
            'graduated'              => Thesis::where('state', 'graduated')->count(),
            'overdue_milestones'     => $overdueMilestoneCount,
        ];

        return view('admin.reports', compact('data', 'byStage', 'supervisorLoad', 'meetingStats'));
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function stageProgressForAdmin(Thesis $thesis): array
    {
        $progress   = [];
        $currentIdx = $thesis->stage_index;

        foreach (Thesis::STAGE_ORDER as $idx => $stage) {
            $progress[$stage] = [
                'name'       => Thesis::STAGE_LABELS[$stage],
                'status'     => $idx < $currentIdx ? 'completed' : ($idx === $currentIdx ? 'current' : 'pending'),
                'submission' => $thesis->getLatestSubmissionForStage($stage),
                'feedback'   => $thesis->getLatestFeedbackForStage($stage),
            ];
        }

        return $progress;
    }
}
