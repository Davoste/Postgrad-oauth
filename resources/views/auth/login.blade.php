
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — {{ brand('name') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ brand('logo_url') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.1); overflow: hidden; }
        :root { --brand-primary: {{ brand('primary_color') }}; --brand-accent: {{ brand('accent_color') }}; --brand-gold: {{ brand('gold_color') }}; }
        .brand-header { background: linear-gradient(180deg, var(--brand-primary) 0%, var(--brand-accent) 100%); color: #fff; padding: 24px 32px; display: flex; align-items: center; justify-content: center; gap: 16px; }
        .brand-header .icon { width: 64px; height: 64px; flex: 0 0 64px; display: flex; align-items: center; justify-content: center; }
        .brand-header .icon img { width: 50px; height: auto; display: block; }
        .brand-copy { text-align: left; }
        .login-body { padding: 32px; }
        .btn-login { background: var(--brand-accent); border: none; padding: 12px; font-weight: 600; letter-spacing: .03em; }
        /* .btn-login:hover { opacity: .9; } */
        .btn-login:hover{ background: var(--brand-gold); }
        .form-control:focus { border-color: var(--brand-primary); box-shadow: 0 0 0 3px rgba(26,60,110,.15); }
        @media (max-width: 575.98px) {
            .brand-header, .login-body { padding: 24px; }
            .brand-header { justify-content: flex-start; gap: 12px; }
            .brand-header .icon { width: 52px; height: 52px; flex-basis: 52px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-7">
            <div class="login-card card">
                <div class="brand-header">
                    <div class="icon"><img src="{{ brand('logo_url') }}" alt="{{ brand('short_name') }} Logo"></div>
                    <div class="brand-copy">
                        <h4 class="mb-1 fw-bold">{{ brand('short_name') }} P.A.T.T.S</h4>
                        <p class="mb-0 opacity-75" style="font-size:14px;">Postgraduate Application and Thesis Tracking System</p>
                    </div>
                </div>

                <div class="login-body">
                    <h5 class="fw-semibold mb-1">Welcome back</h5>
                    <p class="text-muted mb-4" style="font-size:14px;">Sign in to your account to continue</p>

                    @if(session('status'))
                        <div class="alert alert-success alert-sm">{{ session('status') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row g-3 align-items-end mb-3">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">Email Address</label>
                                    <input type="email" id="email" name="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="you@example.com"
                                        required autofocus autocomplete="username">
                                </div>

                                <div class="mb-0">
                                    <div class="d-flex justify-content-between">
                                        <label for="password" class="form-label fw-medium">Password</label>
                                        @if(Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="text-decoration-none" style="font-size:13px;">Forgot password?</a>
                                        @endif
                                    </div>
                                    <input type="password" id="password" name="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="••••••••" required autocomplete="current-password">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-login btn-primary w-100 btn-lg text-white h-100">
                                    Sign In
                                </button>
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label text-muted" for="remember_me" style="font-size:14px;">Remember me for 30 days</label>
                        </div>
                    </form>

                    <p class="text-center text-muted mt-4 mb-0" style="font-size: 12px;">
                        New applicant? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Create an account</a>
                    </p>
                </div>
            </div>

            <p class="text-center text-muted mt-3" style="font-size:12px;">
                &copy; {{ date('Y') }} {{ brand('footer_text') }}
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@include('partials.loader')
</body>
</html>
