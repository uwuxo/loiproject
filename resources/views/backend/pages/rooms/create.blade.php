
@extends('backend.layouts.master')

@section('title')
Room Create - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
    .custom-time-picker {
            position: relative;
        }
        .time-picker-popup {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            display: none;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .time-picker-popup.show {
            display: block;
        }
        .time-section {
            display: inline-block;
            text-align: center;
            padding: 0 10px;
        }
        .time-control {
            width: 40px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 5px 0;
        }
        .time-btn {
            display: block;
            width: 100%;
            background: none;
            border: none;
            padding: 2px;
            cursor: pointer;
        }
        .time-btn:hover {
            background-color: #f0f0f0;
        }
</style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Room Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('gateway') }}">All Room</a></li>
                    <li><span>Room</span></li>
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
                    <h4 class="header-title">Create New Room</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('room.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                
                                
                                <div class="form-group mt-5">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="password">Courses</label>
                                        <select name="groups[]" id="groups" class="form-control select2" multiple>
                                            @foreach ($courses as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
    
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Room</button>
                        <a href="{{ route('gateway') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
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