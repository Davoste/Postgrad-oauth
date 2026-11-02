@extends('layouts.app')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-1">Edit profile</h5>
                <p class="text-muted mb-4">Update your account details.</p>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="surname" class="form-label">Surname</label>
                            <input id="surname" name="surname" type="text" class="form-control" value="{{ old('surname', $user->surname) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input id="first_name" name="first_name" type="text" class="form-control" value="{{ old('first_name', $user->first_name) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Display Name</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required>
                        <small class="text-muted">Automatically updated when surname/first name above are changed.</small>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="mb-4">
                        <label for="id_no" class="form-label">ID / Passport Number</label>
                        <input id="id_no" name="id_no" type="text" class="form-control" value="{{ old('id_no', $user->id_no) }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection