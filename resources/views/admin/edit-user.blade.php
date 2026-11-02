@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User — ' . $user->name)

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i></a>
        <h6 class="mb-0 fw-semibold">Edit: {{ $user->name }}</h6>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-medium">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">New Password <small class="text-muted">(leave blank to keep)</small></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium">Role</label>
                    <select name="role" id="roleSelect" class="form-select" onchange="toggleRoleFields()">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role', optional($userRole)->name) == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Student fields --}}
            <div id="studentFields" class="{{ old('role', optional($userRole)->name) !== 'student' ? 'd-none' : '' }} border rounded p-3 bg-light mb-4">
                <h6 class="fw-semibold mb-3"><i class="fas fa-user-graduate me-2 text-success"></i>Student Details</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Registration No.</label>
                        <input type="text" name="reg_no" class="form-control" value="{{ old('reg_no', optional($student)->reg_no) }}">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-medium">Programme</label>
                        <input type="text" name="program" class="form-control" value="{{ old('program', optional($student)->program) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Admission Year</label>
                        <input type="number" name="admission_year" class="form-control" value="{{ old('admission_year', optional($student)->admission_year) }}" min="2000" max="{{ date('Y') + 1 }}">
                    </div>
                </div>
            </div>

            {{-- Supervisor fields --}}
            <div id="supervisorFields" class="{{ old('role', optional($userRole)->name) !== 'supervisor' ? 'd-none' : '' }} border rounded p-3 bg-light mb-4">
                <h6 class="fw-semibold mb-3"><i class="fas fa-chalkboard-teacher me-2 text-info"></i>Supervisor Details</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', optional($supervisor)->department) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Max Student Capacity</label>
                        <input type="number" name="max_capacity" class="form-control" value="{{ old('max_capacity', optional($supervisor)->max_capacity ?? 5) }}" min="1" max="20">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Changes</button>
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
</script>
@endsection
@endsection
