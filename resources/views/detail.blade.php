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
                    <li><a href="{{ route('home') }}">Home</a></li>
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
                        <table id="dataTable" class="table text-left w-100">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="30%">Courses</th>                                    
                                    <th width="20%">Description</th>
                                    <th width="30%">Schedule</th>
                                    <th width="15%">Start date</th>
                                    <th width="15%">End date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $course->name }}</td>                                    
                                    <td>{{ $course->description }}</td>
                                    <td><div class="weekly-schedule">
                                        @php
                                            $days = [
                                                'monday' => 'Monday',
                                                'tuesday' => 'Tuesday',
                                                'wednesday' => 'Wednesday',
                                                'thursday' => 'Thursday',
                                                'friday' => 'Friday',
                                                'saturday' => 'Saturday',
                                                'sunday' => 'Sunday',
                                            ];
                                            $schedule = $course->schedule;
                                        @endphp
                                                    
                                            @foreach ($days as $dayKey => $dayLabel)
                                            @if (!empty($schedule[$dayKey]['start_time']))
                                            <div class="card">
                                                <div class="pt-3">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-12">
                                                            <div class="row time-inputs">
                                                                <div class="col-md-5">
                                                                    <div class="">{{ $dayLabel }}</div>    
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <div class="">{{ $schedule[$dayKey]['start_time'] }} - {{ $schedule[$dayKey]['end_time'] }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                    </div></td>
                                    <td>{{ Carbon\Carbon::parse($course->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($course->end_date)->format('d/m/Y') }}</td>
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
                        <table id="dataTable" class="table text-left w-100">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="30%">Rooms</th>
                                    <th width="10%">Total</th>
                                    <th>Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rooms = $course->rooms()->get();
                                @endphp
                                @foreach ($rooms as $room)
                                <tr>
                                    <td>{{ $room->name }}</td>
                                    <td>
                                        @if ($room->logged->count() == 0)
                                            <span>{{ $room->logged->count() }}</span>
                                        @else
                                        <span class="alert-success">{{ $room->logged->count() }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($room->logged->count())
                                        @foreach ($room->logged as $logged)
                                            <span class="alert-success">{{ $logged->user->name }}</span>
                                        @endforeach
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
