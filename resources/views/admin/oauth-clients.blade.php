@extends('layouts.app')
@section('title', 'Single Sign-On')
@section('page-title', 'Single Sign-On (OAuth2) Clients')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('newClient'))
    @php($nc = session('newClient'))
    <div class="alert alert-warning">
        <strong>Save these now — the secret will not be shown again.</strong>
        <div class="mt-2 small">
            <div><strong>Client ID:</strong> <code>{{ $nc['client_id'] }}</code></div>
            <div><strong>Client Secret:</strong> <code>{{ $nc['client_secret'] }}</code></div>
            <div><strong>Authorization URL:</strong> <code>{{ url('/oauth/authorize') }}</code></div>
            <div><strong>Token URL:</strong> <code>{{ url('/oauth/token') }}</code></div>
            <div><strong>UserInfo URL:</strong> <code>{{ url('/oauth/userinfo') }}</code></div>
        </div>
    </div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Add a client (e.g. your Moodle instance)</h5>
        <form method="POST" action="{{ route('admin.oauth-clients.store') }}" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Name</label>
                <input name="name" class="form-control" placeholder="Moodle LMS" required>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Redirect URI(s)</label>
                <input name="redirect_uri" class="form-control" placeholder="https://lms.example.edu/admin/oauth2callback.php" required>
                <small class="text-muted">Moodle's OAuth2 callback URL, from Site administration &gt; OAuth 2 services.</small>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="trusted" value="1" id="trusted">
                    <label class="form-check-label small" for="trusted">Trusted (skip consent)</label>
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button class="btn btn-primary w-100">Add</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">Existing clients</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Name</th><th>Client ID</th><th>Redirect URI</th><th>Trusted</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($clients as $c)
                    <tr>
                        <td>{{ $c->name }}</td>
                        <td><code class="small">{{ $c->client_id }}</code></td>
                        <td class="small text-break">{{ $c->redirect_uri }}</td>
                        <td>{{ $c->trusted ? 'Yes' : 'No' }}</td>
                        <td>
                            @if($c->revoked)
                                <span class="badge bg-danger">Revoked</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @unless($c->revoked)
                                <form method="POST" action="{{ route('admin.oauth-clients.toggle-trusted', $c) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-secondary">{{ $c->trusted ? 'Require consent' : 'Trust' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.oauth-clients.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Revoke this client? It will stop working immediately.');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Revoke</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted text-center py-4">No SSO clients yet — add your Moodle instance above.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
