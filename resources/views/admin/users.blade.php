@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage all portal accounts</p>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-user-plus me-2"></i>Add User</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-medium">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, email or Reg no.." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-medium">Role</label>
                <select name="role" class="form-select form-select-sm">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary me-2"><i class="fas fa-search me-1"></i>Search</button>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">Users — {{ $users->total() }} records</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="font-size:12px;">Name</th>
                    <th style="font-size:12px;">Email</th>
                    <th style="font-size:12px;">Role</th>
                    <th style="font-size:12px;">Created</th>
                    <th style="font-size:12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center fw-semibold text-primary flex-shrink-0"
                                style="width:34px;height:34px;font-size:13px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="fw-medium" style="font-size:13px;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td><small class="text-muted">{{ $user->email }}</small></td>
                    <td>
                        @php
                        $roleName = optional($user->roles->first())->name ?? 'none';
                        $roleColors = ['admin'=>'danger','supervisor'=>'info','student'=>'success','ethics_reviewer'=>'warning','hod'=>'primary','dean'=>'secondary','registry'=>'dark'];
                        @endphp
                        <span class="badge bg-{{ $roleColors[$roleName] ?? 'secondary' }}">{{ ucfirst($roleName) }}</span>
                    </td>
                    <td><small class="text-muted">{{ $user->created_at->format('d M Y') }}</small></td>
                    <td>
			@if(optional($user->student)->thesis)
				<a href="{{ route('admin.thesis.detail',$user->student->thesis) }}" class="btn btn-sm btn-outline-primary me-1" title="View student thesis"><i class="fas fa-eye"></i></a>
			@else
				<a href="{{ route('admin.users.edit',$user) }}" class= "btn btn-sm btn-outline-secondary me-1" title="View/ Edit user"><i class= "fas fa-eye"></i></a>
			@endif
			<a href="{{ route('admin.users.edit', $user) }}" class= "btn btn-sm btn-outline-primary me-1"><i class= "fas fa-edit"></i></a>
		
                        @if($user->id !== Auth::id())
                        <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="d-inline"
                              onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white border-top">
        <div class="pagination-wrapper">
            {{ $users->links() }}
        </div>
    </div>

    <style>
        .pagination-wrapper svg {
            width: 20px !important;
            height: 20px !important;
            max-width: 20px !important;
            max-height: 20px !important;
        }
    </style>
@endif
</div>
@endsection
