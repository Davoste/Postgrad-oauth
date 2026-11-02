<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', brand('name') . ' Portal')</title>
    <link rel="icon" type="image/x-icon" href="{{ brand('logo_url') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root { --jooust-blue: {{ brand('primary_color') }}; --jooust-royal: {{ brand('accent_color') }}; --jooust-gold: {{ brand('gold_color') }}; }
        * { font-family: 'Inter', sans-serif; }
        body { color:#1f2937; }
        .navbar-jooust { background: linear-gradient(90deg,var(--jooust-blue) 0%,var(--jooust-royal) 100%); }
        .navbar-jooust .navbar-brand, .navbar-jooust .nav-link { color:#fff !important; }
        .navbar-jooust .nav-link:hover { color:var(--jooust-gold) !important; }
        .btn-gold { background:var(--jooust-gold); color:var(--jooust-blue); font-weight:600; border:none; }
        .btn-gold:hover { background:#dede55; color:var(--jooust-blue); }
        .btn-blue { background:var(--jooust-blue); color:#fff; font-weight:600; border:none; }
        .btn-blue:hover { background:var(--jooust-royal); color:#fff; }
        .text-jooust { color:var(--jooust-blue); }
        .bg-jooust { background:var(--jooust-blue); }
        .hero {
            /* @if(brand('hero_image_url'))
                background: linear-gradient(120deg, rgba(10,20,40,.55), rgba(10,20,40,.55)), url('{{ brand('hero_image_url') }}') center/cover no-repeat;
            @else
                background:linear-gradient(120deg,var(--jooust-royal) 0%,var(--jooust-blue) 100%);
            @endif */
            color:#fff; 
            background:linear-gradient(120deg,var(--jooust-royal) 0%,var(--jooust-blue) 100%);
        }
        .hero2 { background:linear-gradient(120deg,#00ff01 0%,#006600 100%); color:#fff; }
        .hero-overlay {
            @if(brand('hero_image_url'))
                background:
                    linear-gradient(
                        90deg,
                        rgba(8,26,45,.94) 0%,
                        rgba(8,26,45,.74) 48%,
                        rgba(8,26,45,.20) 100%
                    ),
                    url('{{ brand('hero_image_url') }}') center/cover no-repeat;
            @else
                background:linear-gradient(120deg,var(--jooust-royal) 0%,var(--jooust-blue) 100%);
            @endif
            color:#fff;

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .section-divider {
            width: 100%;
            height: 4px;
            background: #cccc33;
            margin: 0;
        }
        .section-divider1 {
            width: 100%;
            height: 16px;
            background: transparent;
            border: 2px solid #52b933;
            margin: 0;
            box-sizing: border-box;
        }
        .section-label { color:var(--jooust-gold); font-weight:700; letter-spacing:.14em; text-transform:uppercase; font-size:12px; }
        .section-label1 { color:#52b933 ; font-weight:700; letter-spacing:.14em; text-transform:uppercase; font-size:16px; }
        .form-control:focus,.form-select:focus { border-color:var(--jooust-blue); box-shadow:0 0 0 .2rem rgba(26,60,110,.15); }
        footer.site-footer { background: var(--jooust-blue); color:rgba(255,255,255,.75); }
        fieldset.form-card { border:1px solid rgba(26,60,110,.12); border-radius:16px; background:#fff; }
        fieldset.form-card legend { font-size:1rem; font-weight:700; color:var(--jooust-blue); padding:0 .5rem; width:auto; }
    </style>
    @yield('styles')
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-jooust shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('welcome') }}">
            <img src="{{ brand('logo_url') }}" alt="{{ brand('short_name') }}" style="width:38px;height:auto;">
            <span class="fw-bold">{{ brand('short_name') }} <span class="fw-normal opacity-75">Postgraduate</span></span>
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('programmes.index') }}">Programmes</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}#how">How to apply</a></li>
                <!-- <li class="nav-item ms-lg-2"><a class="btn btn-gold btn-sm px-3" href="{{ route('apply') }}">Apply now</a></li> -->
                @auth 
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm px-3" href="{{ route('redirect') }}">Dashboard</a></li>
                @else
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm px-3" href="{{ route('login') }}">Sign in</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm px-3" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="container mt-3"><div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div></div>
@endif

@yield('content')

<footer class="site-footer py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-5">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ brand('logo_url') }}" alt="{{ brand('short_name') }}" style="width:40px;height:auto;">
                    <span class="fw-bold text-white">{{ brand('name') }} Portal</span>
                </div>
                <p class="small mb-0">Board of Postgraduate Studies — {{ brand('name') }}, {{ brand('address') }}.</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-white">Quick links</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('apply') }}" class="text-decoration-none text-white-50">Apply for admission</a></li>
                    <li><a href="{{ route('login') }}" class="text-decoration-none text-white-50">Portal sign in</a></li>
                    <li><a href="{{ route('welcome') }}#how" class="text-decoration-none text-white-50">How to apply</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white">Contact</h6>
                <p class="small mb-0"><i class="fas fa-envelope me-2"></i>{{ brand('support_email') }}</p>
                <p class="small"><i class="fas fa-map-marker-alt me-2"></i>{{ brand('address') }}</p>
            </div>
        </div>
        <hr class="border-secondary">
        <div class="small text-center">&copy; {{ date('Y') }} {{ brand('footer_text') }}</div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
@include('partials.loader')
</body>
</html>
