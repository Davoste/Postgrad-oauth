<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password — {{ brand('name') }} Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root { --brand-primary: {{ brand('primary_color') }}; --brand-accent: {{ brand('accent_color') }}; }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; }
        .login-card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.1); }
        .brand-header { background: linear-gradient(180deg, var(--brand-primary) 0%, var(--brand-accent) 100%); color: #fff; padding: 32px; border-radius: 16px 16px 0 0; text-align: center; }
        .brand-header .icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 28px; }
        .login-body { padding: 32px; }
        .btn-submit { background: var(--brand-accent); border: none; padding: 12px; font-weight: 600; letter-spacing: .03em; }
        .btn-submit:hover { background: var(--brand-primary); }
        .form-control:focus { border-color: var(--brand-primary); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="login-card card">
                <div class="brand-header">
                    <div class="icon"><img src="{{ brand('logo_url') }}" alt="{{ brand('short_name') }}" style="width: 50px; height: auto; display: block;"></div>
                    <h4 class="mb-1 fw-bold">{{ brand('name') }}</h4>
                    <p class="mb-0 opacity-75" style="font-size:14px;">{{ brand('tagline') }}</p>
                </div>

                <div class="login-body">
                    <h5 class="fw-semibold mb-1">Reset your password</h5>
                    <p class="text-muted mb-4" style="font-size:14px;">
                        Enter your registered email address and we'll send you a password reset link.
                    </p>

                    @if(session('status'))
                        <div class="alert alert-success" style="font-size:14px;">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger" style="font-size:14px;">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">Email Address</label>
                            <input type="email" id="email" name="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="you@example.com"
                                required autofocus autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-submit btn-primary w-100 btn-lg text-white mb-3">
                            <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                        </button>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-decoration-none" style="font-size:13px; color:var(--brand-primary);">
                                <i class="fas fa-arrow-left me-1"></i>Back to Sign In
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-3" style="font-size:12px;">
                &copy; {{ date('Y') }} {{ brand('name') }} Portal. All rights reserved.
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
