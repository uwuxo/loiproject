@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="my-4">Edit Profile</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.update', $user->id) }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="mb-3">
                <label for="allowed_days" class="form-label">Status</label><br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" {{ $user->status ? 'checked' : '' }} value="1">
                    <label class="form-check-label" for="monday">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" {{ !$user->status ? 'checked' : '' }} value="0">
                    <label class="form-check-label" for="tuesday">Inactive</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    @endsection
