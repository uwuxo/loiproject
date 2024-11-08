@extends('backend.layouts.master')

@section('title')
    Attendances - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection


@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Attendances</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><span>Attendances</span></li>
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
                        <h4 class="header-title float-left">Attendances List</h4>
                        <div class="float-right mb-2">
                            <form action="" method="GET">
                                <div class="input-group">
                                    <input name="search" type="text" class="form-control" value="{{ $validated['search'] ?? ''}}" placeholder="Search for User">
                                    <input name="start_date" type="date" class="form-control date" value="{{ $validated['start_date'] ?? ''}}" placeholder="Start Date" required>
                                    <input name="end_date" type="date" class="form-control" value="{{ $validated['end_date'] ?? ''}}" placeholder="End Date" required>
                                    <select name="course_id" class="input-group-append">
                                        @foreach ($courses as $course)
                                            <option value="">Choose Course</option>
                                            <option {{ $validated['course_id'] ?? null == $course->id ? 'selected' : ''}} value="{{ $course->id }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="room_id" class="input-group-append">
                                        <option value="">Choose Room</option>
                                        @foreach ($rooms as $room)
                                            <option {{ $validated['room_id'] ?? null == $room->id ? 'selected' : ''}} value="{{ $room->id }}">{{ $room->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                    </div>
                                    <div class="input-group-append">
                                        <a href="{{ route('attendance') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                    @if(request()->query())
                                    <div class="input-group-append">
                                    <a href="{{ route('attendance.export', request()->query()) }}" class="btn btn-success">
                                        Export to Excel
                                    </a></div>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('backend.layouts.partials.messages')
                            <table class="table w-100%">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th width="20%">User Name</th>
                                        <th width="10%">UID</th>
                                        <th width="20%">Course</th>
                                        <th width="10%">Rooms</th>
                                        <th width="10%">Date</th>
                                        <th width="13%">Check-in Time</th>
                                        <th width="13%">Check-out Time</th>
                                        <th width="5%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($attendances) > 0)
                                        @foreach ($attendances as $attendance)
                                            <tr>
                                                <td>{{ $attendance->user->name }}</td>
                                                <td>{{ $attendance->user->username }}</td>
                                                <td>{{ $attendance->course->name }}</td>
                                                <td>{{ $attendance->room->name }}</td>
                                                <td>{{ $attendance->attendance_date }}
                                                </td>
                                                <td>{{ $attendance->check_in_time }}</td>
                                                <td>{{ $attendance->check_out_time }}</td>
                                                <td>
                                                    @if ($attendance->check_in_time && $attendance->check_out_time)
                                                        <span class="alert-success">Complete</span>
                                                    @else
                                                        <span class="alert-danger">Incomplete</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No Attendances Found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- data table end -->

        </div>
    </div>
@endsection

