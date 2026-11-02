<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authorize {{ $client->name }} — {{ brand('name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ brand('logo_url') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --brand-primary: {{ brand('primary_color') }}; --brand-accent: {{ brand('accent_color') }}; }
        * { font-family: 'Inter', sans-serif; }
        body { background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,.1); }
        .header { background: linear-gradient(180deg, var(--brand-primary) 0%, var(--brand-accent) 100%); color:#fff; padding: 28px; border-radius: 16px 16px 0 0; text-align:center; }
        .btn-allow { background: var(--brand-accent); color:#fff; border:none; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="header">
                    <img src="{{ brand('logo_url') }}" style="width:44px;height:auto;" alt="{{ brand('short_name') }}">
                    <h5 class="fw-bold mt-2 mb-0">{{ brand('short_name') }} Account</h5>
                </div>
                <div class="card-body p-4">
                    <p class="mb-3">
                        <strong>{{ $client->name }}</strong> wants to sign you in and access your basic
                        profile (name, email{{ $scopes && str_contains($scopes, 'idnumber') ? ', admission number' : '' }}).
                    </p>
                    <p class="text-muted small mb-4">Signed in as <strong>{{ auth()->user()->email }}</strong></p>

                    <form method="POST" action="{{ route('oauth.approve') }}">
                        @csrf
                        <input type="hidden" name="client_id" value="{{ $client->client_id }}">
                        <input type="hidden" name="redirect_uri" value="{{ $redirectUri }}">
                        <input type="hidden" name="scope" value="{{ $scopes }}">
                        <input type="hidden" name="state" value="{{ $state }}">
                        <button type="submit" class="btn btn-allow w-100 mb-2">Allow</button>
                    </form>
                    <form method="POST" action="{{ route('oauth.deny') }}">
                        @csrf
                        <input type="hidden" name="redirect_uri" value="{{ $redirectUri }}">
                        <input type="hidden" name="state" value="{{ $state }}">
                        <button type="submit" class="btn btn-outline-secondary w-100">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
