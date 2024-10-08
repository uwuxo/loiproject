
@extends('backend.layouts.master')

@section('title')
Room Edit - Admin Panel
@endsection

@section('styles')
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
                <h4 class="page-title pull-left">Room Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('rooms', $room->course->id) }}">All Room</a></li>
                    <li><span>Edit Room - {{ $room->name }}</span></li>
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
                    <h4 class="header-title">Edit Room - {{ $room->name }}</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('room.update', $room->id) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Room Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $room->name) }}" required>
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="allowed_days" class="form-label">Allowed Login Days</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="1" id="monday" {{ $allowed_days && in_array(1, $allowed_days) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="monday">Monday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="2" id="tuesday" {{ $allowed_days && in_array(2, $allowed_days) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tuesday">Tuesday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="3" id="wednesday" {{ $allowed_days && in_array(3, $allowed_days) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="wednesday">Wednesday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="4" id="thursday" {{ $allowed_days && in_array(4, $allowed_days) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="thursday">Thursday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="5" id="friday" {{ $allowed_days && in_array(5, $allowed_days) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="friday">Friday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="6" id="saturday" {{ $allowed_days && in_array(6, $allowed_days) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="saturday">Saturday</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="0" id="sunday" {{ $allowed_days && in_array(0, $allowed_days) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sunday">Sunday</label>
                                </div>
                            </div>
                            </div>
                            
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Room</button>
                        <a href="{{ route('rooms', $room->course->id) }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->
        
    </div>
</div>
@endsection