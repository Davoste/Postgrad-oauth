<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', brand('short_name') . ' Portal')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ brand('logo_url') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --brand-primary: {{ brand('primary_color') }};
            --brand-accent: {{ brand('accent_color') }};
            --brand-gold: {{ brand('gold_color') }};
        }
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; }

        /* Sidebar */
        .sidebar {
            width: 250px; height: 100vh; height: 100dvh;
            background: linear-gradient(180deg, var(--brand-primary) 0%, var(--brand-accent) 100%);
            position: fixed; top: 0; left: 0; z-index: 1000;
            display: flex; flex-direction: column;
            transition: width .3s;
            overflow: visible;
        }
        .sidebar-brand { padding: 20px 16px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-brand img { width: 40px; height: 40px; object-fit: contain; }
        .sidebar-brand .brand-text { color: #fff; font-size: 13px; font-weight: 700; line-height: 1.2; }
        .sidebar-nav { flex: 1; padding: 16px 0; overflow-y: auto; min-height: 0; }
        .sidebar-nav .nav-section-label {
            padding: 8px 16px 4px; font-size: 10px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .08em; color: rgba(255,255,255,.4);
        }
        .sidebar-nav .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; color: #fff; font-size: 14px;
            border-radius: 0; transition: all .2s; border-left: 3px solid transparent;
        }
        .sidebar-nav .nav-link:hover, .sidebar-nav .nav-link.active {
            color: var(--brand-accent); background: var(--brand-gold); border-left-color: #00FF01;
        }
        .sidebar-nav .nav-link i { width: 18px; text-align: center; font-size: 14px; }
        .sidebar-nav .nav-link.disabled {
            opacity: .45; cursor: not-allowed; pointer-events: none;
        }
        .sidebar-nav .nav-link.disabled:hover { background: transparent; color: #fff; border-left-color: transparent; }
        .sidebar-footer {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,.1);
            background: rgba(255,255,255,.04);
            flex-shrink: 0;
        }
        .sidebar-footer .user-info { color: rgba(255,255,255,.8); font-size: 12px; }
        .sidebar-footer .user-name { color: #fff; font-weight: 600; font-size: 14px; }
        .sidebar-footer .btn {
            display: block;
            width: 100%;
            background: rgba(58, 146, 218, 0.16);
            font-size: 12px;
            color: #fff;
            border: 0;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background .2s, color .2s;
            font-weight: 600;
        }
        .sidebar-footer .btn:hover { background: #CCCC33; color: #0000FF; }
        .account-menu { position: relative; }
        .account-trigger {
            width: 100%; padding: 0; border: 0; background: transparent;
            color: inherit; text-align: left;
        }
        .account-trigger::after { margin-left: auto; color: rgba(255,255,255,.7); }
        .account-trigger:hover, .account-trigger:focus { color: #fff; }
        .account-dropdown {
            width: 218px; margin: 0 0 8px; padding: 6px;
            border: 1px solid rgba(26,60,110,.12); border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,.18);
        }
        .account-dropdown .dropdown-item {
            padding: 9px 10px; border-radius: 5px; font-size: 13px;
        }
        .account-dropdown .dropdown-item i { width: 18px; color: #1a3c6e; }
        .account-dropdown .dropdown-item:hover { background: #eef3fb; color: #1a3c6e; }
        .account-dropdown form { margin: 0; }
        .account-dropdown button.dropdown-item { border: 0; background: transparent; }
        .account-dropdown .account-user { display: none; padding: 8px 10px 10px; }
        .account-dropdown .account-user-name { font-weight: 600; color: #1a3c6e; }
        .account-dropdown .account-user-role { color: #6b7280; font-size: 11px; }

        /* Main content */
        .main-content { margin-left: 250px; min-width: 0; min-height: 100vh; }
        .top-bar {
            background: #fff; border-bottom: 1px solid #e5e7eb;
            padding: 12px 24px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .page-content { padding: 24px; }

        /* Cards */
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .stat-card .icon-wrap { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; }

        /* Badges */
        .stage-badge { font-size: 11px; padding: 4px 10px; border-radius: 20px; font-weight: 500; }

        /* Timeline */
        .timeline { position: relative; padding-left: 28px; }
        .timeline::before { content: ''; position: absolute; left: 11px; top: 0; bottom: 0; width: 2px; background: #e5e7eb; }
        .timeline-item { position: relative; margin-bottom: 24px; }
        .timeline-dot {
            position: absolute; left: -22px; top: 2px;
            width: 24px; height: 24px; border-radius: 50%;
            background: #9ca3af; color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: 10px;
        }
        .timeline-item.completed .timeline-dot { background: #22c55e; }
        .timeline-item.current .timeline-dot { background: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.25); }
        .timeline-content { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px 16px; }
        .timeline-item.current .timeline-content { border-color: #3b82f6; background: #eff6ff; }
        .timeline-item.completed .timeline-content { border-color: #86efac; background: #f0fdf4; }

        @media (max-width: 768px) {
            .sidebar { width: 60px; }
            .sidebar-brand img { width: 32px !important; }
            .sidebar-brand .brand-text, .sidebar-nav .nav-link span, .sidebar-nav .nav-section-label, .sidebar-footer .user-info, .sidebar-footer .user-name, .account-trigger .account-label { display: none; }
            .main-content { margin-left: 60px; width: calc(100% - 60px); }
            .top-bar { padding: 10px 14px; }
            .top-bar small { display: none; }
            .page-content { padding: 16px 12px; }
            .sidebar-footer { padding: 10px 8px; }
            .sidebar-footer .account-trigger > div:first-child { width: 32px !important; height: 32px !important; }
            .sidebar-footer .account-trigger > div:first-child i { font-size: 12px !important; }
            .account-dropdown { left: 0; }
            .account-dropdown .account-user { display: block; }
        }
    </style>

    @yield('styles')
</head>
<body>

@auth
<!-- Sidebar Navigation -->
<aside class="sidebar">
    <div class="sidebar-brand d-flex align-items-center gap-2">
        <div style=" border-radius: 8px; padding: 4px; flex-shrink: 0;">
            <!-- <i class="fas fa-graduation-cap text-primary" style="font-size: 22px; width: 32px; text-align: center;"></i> -->
             <img src="{{ brand('logo_url') }}" alt="{{ brand('short_name') }} Logo" style="width: 50px; height: auto; display: block;">
        </div>
        <div class="brand-text">
            <div>{{ brand('short_name') }}</div>
            <div style="font-weight:400; opacity:.8; font-size:11px;">{{ brand('tagline') }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @include('layouts.navigation')
    </nav>

    <div class="sidebar-footer account-menu dropup">
        <button type="button" class="account-trigger d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <div style="width:36px;height:36px;background:rgba(255,255,255,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-user text-white" style="font-size:14px;"></i>
            </div>
            <div class="overflow-hidden account-label">
                <div class="user-name text-truncate">{{ Auth::user()->name }}</div>
                <div class="user-info text-truncate">{{ optional(Auth::user()->roles->first())->name ?? 'user' }}</div>
            </div>
        </button>
        <div class="dropdown-menu account-dropdown">
            <div class="account-user">
                <div class="account-user-name text-truncate">{{ Auth::user()->name }}</div>
                <div class="account-user-role text-truncate">{{ optional(Auth::user()->roles->first())->name ?? 'user' }}</div>
            </div>
            <div class="dropdown-divider mt-0"></div>
            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                <i class="fas fa-user-pen me-2"></i>Edit profile
            </a>
            <a href="{{ route('password.edit') }}" class="dropdown-item">
                <i class="fas fa-key me-2"></i>Change password
            </a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">
                    <i class="fas fa-right-from-bracket me-2"></i>Logout
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Main content -->
<div class="main-content">
    <!-- Top bar -->
    <div class="top-bar">
        <h6 class="mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h6>
        <div class="d-flex align-items-center gap-3">
            <small class="text-muted">{{ now()->format('D, d M Y') }}</small>
        </div>
    </div>

    <!-- Flash messages -->
    <div class="page-content pb-0 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @yield('content')
    </div>
</div>

@else
<!-- Guest: plain wrapper -->
<div>
    @yield('content')
</div>
@endauth

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')
@include('partials.loader')
</body>
</html>
