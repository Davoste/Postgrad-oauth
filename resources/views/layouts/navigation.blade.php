@php $user = Auth::user(); @endphp

@if($user->hasRole('admin'))
    <div class="nav-section-label">Administration</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
    </a>
    <a href="{{ route('admin.monitor') }}" class="nav-link {{ request()->routeIs('admin.monitor') ? 'active' : '' }}">
        <i class="fas fa-eye"></i><span>Monitor Students</span>
    </a>
    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <i class="fas fa-users"></i><span>Users</span>
    </a>
    <a href="{{ route('admissions.index') }}" class="nav-link {{ request()->routeIs('admissions.*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-check"></i><span>Admissions</span>
    </a>
    <a href="{{ route('admin.structure') }}" class="nav-link {{ request()->routeIs('admin.structure*') ? 'active' : '' }}">
        <i class="fas fa-sitemap"></i><span>Structure</span>
    </a>
    <a href="{{ route('admin.programmes.index') }}" class="nav-link {{ request()->routeIs('admin.programmes*') ? 'active' : '' }}">
        <i class="fas fa-book"></i><span>Programmes</span>
    </a>
    <a href="{{ route('executive.dashboard') }}" class="nav-link {{ request()->routeIs('executive.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i><span>Insights</span>
    </a>
    <a href="{{ route('admin.theses') }}" class="nav-link {{ request()->routeIs('admin.theses*') ? 'active' : '' }}">
        <i class="fas fa-book-open"></i><span>Theses</span>
    </a>
    <a href="{{ route('admin.supervisions') }}" class="nav-link {{ request()->routeIs('admin.supervisions') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i><span>Supervisions</span>
    </a>
    <a href="{{ route('admin.thesis-reviews') }}" class="nav-link {{ request()->routeIs('admin.thesis-reviews') ? 'active' : '' }}">
        <i class="fas fa-tasks"></i><span>Thesis Reviews</span>
    </a>
    <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i><span>Reports</span>
    </a>
    <a href="{{ route('admin.branding.edit') }}" class="nav-link {{ request()->routeIs('admin.branding.edit') ? 'active' : '' }}">
        <i class="fas fa-palette"></i><span>Branding</span>
    </a>
    <a href="{{ route('admin.oauth-clients.index') }}" class="nav-link {{ request()->routeIs('admin.oauth-clients.index') ? 'active' : '' }}">
        <i class="fas fa-key"></i><span>Single Sign-On</span>
    </a>

@elseif($user->hasRole('supervisor'))
    <div class="nav-section-label">Supervision</div>
    <a href="{{ route('supervisor.dashboard') }}" class="nav-link {{ request()->routeIs('supervisor.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
    </a>
    <a href="{{ route('supervision.log') }}" class="nav-link {{ request()->routeIs('supervision.log') ? 'active' : '' }}">
        <i class="fas fa-pen-alt"></i><span>Log Meeting</span>
    </a>
    <a href="{{ route('supervision.meetings') }}" class="nav-link {{ request()->routeIs('supervision.meetings') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i><span>All Meetings</span>
    </a>
    <a href="{{ route('admin.thesis-reviews') }}" class="nav-link {{ request()->routeIs('admin.thesis-reviews') ? 'active' : '' }}">
        <i class="fas fa-tasks"></i><span>Pending Reviews</span>
    </a>
    <a href="{{ route('supervisor.progress-reports') }}" class="nav-link {{ request()->routeIs('supervisor.progress-reports') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i><span>Progress Reports</span>
    </a>

@elseif($user->hasRole('student'))
    @php $admitted = $user->isAdmitted(); @endphp
    <div class="nav-section-label">My Thesis</div>
    <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i><span>Dashboard</span>
    </a>
    <a href="{{ route('apply.portal') }}" class="nav-link {{ request()->routeIs('apply.portal') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i><span>Apply</span>
    </a>

    @if($admitted)
        <a href="{{ route('thesis.dashboard') }}" class="nav-link {{ request()->routeIs('thesis.dashboard') ? 'active' : '' }}">
            <i class="fas fa-book"></i><span>Thesis Progress</span>
        </a>
        <a href="{{ route('thesis.submissions') }}" class="nav-link {{ request()->routeIs('thesis.submissions') ? 'active' : '' }}">
            <i class="fas fa-upload"></i><span>Submissions</span>
        </a>
        <a href="{{ route('thesis.feedback') }}" class="nav-link {{ request()->routeIs('thesis.feedback') ? 'active' : '' }}">
            <i class="fas fa-comments"></i><span>Feedback</span>
        </a>
        <a href="{{ route('thesis.milestones') }}" class="nav-link {{ request()->routeIs('thesis.milestones') ? 'active' : '' }}">
            <i class="fas fa-flag"></i><span>Milestones</span>
        </a>
        <a href="{{ route('supervision.meetings') }}" class="nav-link {{ request()->routeIs('supervision.meetings') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i><span>Meetings</span>
        </a>
        @if(config('moodle.base_url'))
            <a href="{{ config('moodle.base_url') }}" target="_blank" rel="noopener" class="nav-link">
                <i class="fas fa-chalkboard-teacher"></i><span>Learning Environment</span>
                <i class="fas fa-arrow-up-right-from-square ms-auto" style="font-size:10px;opacity:.6;"></i>
            </a>
        @endif
        <div class="nav-section-label">Finance</div>
        <a href="{{ route('student.fees') }}" class="nav-link {{ request()->routeIs('student.fees') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i><span>Fee Statement</span>
        </a>
    @else
        @foreach([
            ['icon' => 'fa-book', 'label' => 'Thesis Progress'],
            ['icon' => 'fa-upload', 'label' => 'Submissions'],
            ['icon' => 'fa-comments', 'label' => 'Feedback'],
            ['icon' => 'fa-flag', 'label' => 'Milestones'],
            ['icon' => 'fa-calendar-check', 'label' => 'Meetings'],
            ['icon' => 'fa-chalkboard-teacher', 'label' => 'Learning Environment'],
        ] as $locked)
            <span class="nav-link disabled" title="Unlocks once you're admitted">
                <i class="fas {{ $locked['icon'] }}"></i><span>{{ $locked['label'] }}</span>
                <i class="fas fa-lock ms-auto" style="font-size:11px;opacity:.7;"></i>
            </span>
        @endforeach
        <div class="nav-section-label">Finance</div>
        <span class="nav-link disabled" title="Unlocks once you're admitted">
            <i class="fas fa-file-invoice-dollar"></i><span>Fee Statement</span>
            <i class="fas fa-lock ms-auto" style="font-size:11px;opacity:.7;"></i>
        </span>
    @endif

@elseif($user->hasRole('ethics_reviewer'))
    <div class="nav-section-label">Ethics Review</div>
    <a href="{{ route('ethics.pending') }}" class="nav-link {{ request()->routeIs('ethics*') ? 'active' : '' }}">
        <i class="fas fa-shield-alt"></i><span>Pending Reviews</span>
    </a>

@elseif($user->hasRole('hod') || $user->hasRole('dean'))
    <div class="nav-section-label">Postgraduate Office</div>
    <a href="{{ route('portal.home') }}" class="nav-link {{ request()->routeIs('portal.home') ? 'active' : '' }}">
        <i class="fas fa-th-large"></i><span>Dashboard</span>
    </a>
    <a href="{{ route('admissions.index') }}" class="nav-link {{ request()->routeIs('admissions.*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-check"></i><span>Admissions</span>
    </a>
    @can('view_executive_dashboard')
    <a href="{{ route('executive.dashboard') }}" class="nav-link {{ request()->routeIs('executive.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i><span>Insights</span>
    </a>
    @endcan
    
    

@elseif($user->hasRole('finance'))
    <div class="nav-section-label">Finance Office</div>
    <a href="{{ route('finance.dashboard') }}" class="nav-link {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
    </a>
    <a href="{{ route('finance.invoices') }}" class="nav-link {{ request()->routeIs('finance.invoices') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i><span>Invoices</span>
    </a>

@elseif($user->hasRole('director_bps') || $user->hasRole('registrar') || $user->hasRole('committee'))
    <div class="nav-section-label">Postgraduate Office</div>
    <a href="{{ route('portal.home') }}" class="nav-link {{ request()->routeIs('portal.home') ? 'active' : '' }}">
        <i class="fas fa-home"></i><span>Dashboard</span>
    </a>
    @can('view_applications')
    <a href="{{ route('admissions.index') }}" class="nav-link {{ request()->routeIs('admissions.*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-check"></i><span>Admissions</span>
    </a>
    @endcan
@elseif($user->hasRole('executive'))
    <div class="nav-section-label">Management</div>
    <a href="{{ route('executive.dashboard') }}" class="nav-link {{ request()->routeIs('executive.*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i><span>Insights</span>
    </a>

@endif
