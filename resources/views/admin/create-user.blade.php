@extends('layouts.app')
@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
        <h6 class="mb-0 fw-semibold">New User Account</h6>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Dr. Jane Smith" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="user@university.ac.ke" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 8 characters" required minlength="8">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Role <span class="text-danger">*</span></label>
                    <select name="role" id="roleSelect" class="form-select @error('role') is-invalid @enderror" required onchange="toggleRoleFields()">
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Student fields --}}
            <div id="studentFields" class="d-none border rounded p-3 bg-light mb-4">
                <h6 class="fw-semibold mb-3"><i class="fas fa-user-graduate me-2 text-success"></i>Student Details</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Registration No. <span class="text-danger">*</span></label>
                        <input type="text" name="reg_no" class="form-control @error('reg_no') is-invalid @enderror"
                               value="{{ old('reg_no') }}" placeholder="PG-2024-0001">
                        @error('reg_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-medium">Programme <span class="text-danger">*</span></label>
                        <input type="text" name="program" class="form-control @error('program') is-invalid @enderror"
                               value="{{ old('program') }}" placeholder="Master of Computer Science">
                        @error('program')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Admission Year <span class="text-danger">*</span></label>
                        <input type="number" name="admission_year" class="form-control @error('admission_year') is-invalid @enderror"
                               value="{{ old('admission_year', date('Y')) }}" min="2000" max="{{ date('Y') + 1 }}">
                        @error('admission_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Supervisor fields --}}
            <div id="supervisorFields" class="d-none border rounded p-3 bg-light mb-4">
                <h6 class="fw-semibold mb-3"><i class="fas fa-chalkboard-teacher me-2 text-info"></i>Supervisor Details</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Department <span class="text-danger">*</span></label>
                        <input type="text" name="department" class="form-control @error('department') is-invalid @enderror"
                               value="{{ old('department') }}" placeholder="Computer Science">
                        @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Max Student Capacity</label>
                        <input type="number" name="max_capacity" class="form-control" value="{{ old('max_capacity', 5) }}" min="1" max="20">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Create User</button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

</div>
</div>

@section('scripts')
<script>
function toggleRoleFields() {
    const role = document.getElementById('roleSelect').value;
    document.getElementById('studentFields').classList.toggle('d-none', role !== 'student');
    document.getElementById('supervisorFields').classList.toggle('d-none', role !== 'supervisor');
}
// On page load (for old() values)
toggleRoleFields();
</script>
@endsection
@endsection
