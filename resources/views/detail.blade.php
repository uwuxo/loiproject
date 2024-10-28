@extends('backend.layouts.master')

@section('title')
Home | Security card project
@endsection

@section('admin-content')
<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Home</h4>
                <ul class="breadcrumbs pull-left">
                    @hasanyrole('super-admin|course-admin|course-edit|course-view|user-admin|user-edit|user-view')
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @endhasanyrole
                    <li><span>{{ auth()->user()->type }}</span></li>
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
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Course Infomation</h4>
                    <div class="data-tables">
                        @if (!empty($course))
                        <table id="dataTable" class="text-left w-100">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="30%">Courses</th>                                    
                                    <th width="30%">Description</th>
                                    <th width="15%">Start date</th>
                                    <th width="15%">End date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $course->name }}</td>                                    
                                    <td>{{ $course->description }}</td>
                                    <td>{{ Carbon\Carbon::parse($course->start_date)->format('m/d/Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($course->end_date)->format('m/d/Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @else
                        <p>No courses</p>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="header-title">Rooms List</h4>
                    <div class="data-tables">
                        <table id="dataTable" class="text-left w-100">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="30%">Rooms</th>
                                    <th width="10%">Start time</th>
                                    <th width="10%">End time</th>
                                    <th width="30%">Days</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rooms = $course->rooms()->get();
                                @endphp
                                @foreach ($rooms as $room)
                                <tr>
                                    <td>{{ $room->name }}</td>
                                    <td>{{ $room->start_time }}</td>
                                    <td>{{ $room->end_time }}</td>
                                    <td>
                                        @php
                                        $allowed_days = [];
                                        $allowed_days = json_decode($room->allowed_days, true);
                                        @endphp
                                        @if (!empty($allowed_days))
                                        @if(in_array(1, $allowed_days))
                                        <p>Monday</p>
                                        @endif
                                        @if(in_array(2, $allowed_days))
                                        <p>Tuesday</p>
                                        @endif
                                        @if(in_array(3, $allowed_days))
                                        <p>Wednesday</p>
                                        @endif
                                        @if(in_array(4, $allowed_days))
                                        <p>Thursday</p>
                                        @endif
                                        @if(in_array(5, $allowed_days))
                                        <p>Friday</p>
                                        @endif
                                        @if(in_array(6, $allowed_days))
                                        <p>Saturday</p>
                                        @endif
                                        @if(in_array(0, $allowed_days))
                                        <p>Sunday</p>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
