
@extends('backend.layouts.master')

@section('title')
Group Edit - Admin Panel
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
                <h4 class="page-title pull-left">Group Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('group.index') }}">All Group</a></li>
                    <li><span>Edit Group - {{ $group->name }}</span></li>
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
                    <h4 class="header-title">Edit Group - {{ $group->name }}</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('group.update', $group->id) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Group Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $group->name) }}" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="allowed_days" class="form-label">Status</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" {{ $group->status ? 'checked' : '' }} value="1">
                                    <label class="form-check-label" for="monday">Active</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" {{ !$group->status ? 'checked' : '' }} value="0">
                                    <label class="form-check-label" for="tuesday">Inactive</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Users</label>
                                <select name="users[]" id="users" class="form-control select2" multiple>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, $group->users->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Group</button>
                        <a href="{{ route('group.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
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