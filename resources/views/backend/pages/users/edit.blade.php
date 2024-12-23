@extends('backend.layouts.master')

@section('title')
    User Edit - Admin Panel
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .form-check-label {
            text-transform: capitalize;
        }
    </style>
@endsection


@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Edit</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('users.index') }}">All Users</a></li>
                        <li><span>Edit User - {{ $user->name }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 clearfix">
                @include('backend.layouts.partials.logout')
            </div>
        </div>
    </div>
    <!-- page title area end -->

    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Edit User - {{ $user->name }}</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('user.update', $user->id) }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="email">UID</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="{{ old('username', $user->username) }}" required>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="student" {{ $user->type == 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="teacher" {{ $user->type == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                        <option value="admin" {{ $user->type == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>

                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Leave blank if you don't want to change">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation">
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="allowed_days" class="form-label">Status</label><br>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status"
                                            {{ $user->status ? 'checked' : '' }} value="1">
                                        <label class="form-check-label" for="monday">Active</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status"
                                            {{ !$user->status ? 'checked' : '' }} value="0">
                                        <label class="form-check-label" for="tuesday">Inactive</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="password">Courses</label>

                                    <select name="groups[]" id="groups" class="form-control select2" multiple>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ in_array($group->id, $user->courses->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                {{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="password">Roles</label>

                                    <select name="roles[]" id="roles" class="form-control select2" multiple>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save User</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
            <!-- data table end -->

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        })
    </script>
@endsection
