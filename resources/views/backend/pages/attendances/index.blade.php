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
                        <li><a href="{{ route('home') }}">Home</a></li>
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
                                    <select id="course" name="course_id" class="input-group-append">
                                        <option value="">Choose Course</option>
                                        @foreach ($courses as $course)
                                            <option {{ isset($validated['course_id']) && $validated['course_id'] == $course->id ? 'selected' : ''}} value="{{ $course->id }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                    <select id="room" name="room_id" class="input-group-append">
                                        <option value="">Choose Room</option>
                                    </select>
                                    <select id="range" name="range" class="input-group-append">
                                        <option value="">Range</option>
                                        <option {{ isset($validated['range']) && $validated['range'] == '5' ? 'selected' : ''}} value="5">5 minutes range</option>
                                        <option {{ isset($validated['range']) && $validated['range'] == '10' ? 'selected' : ''}} value="10">10 minutes range</option>
                                        <option {{ isset($validated['range']) && $validated['range'] == '15' ? 'selected' : ''}} value="15">15 minutes range</option>
                                        <option {{ isset($validated['range']) && $validated['range'] == '20' ? 'selected' : ''}} value="20">20 minutes range</option>
                                        <option {{ isset($validated['range']) && $validated['range'] == '25' ? 'selected' : ''}} value="25">25 minutes range</option>
                                        <option {{ isset($validated['range']) && $validated['range'] == '30' ? 'selected' : ''}} value="30">30 minutes range</option>

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
                                        <th width="15%">User Name</th>
                                        <th width="10%">UID</th>
                                        <th width="20%">Course</th>
                                        <th width="10%">Rooms</th>
                                        <th width="10%">Date</th>
                                        <th width="13%">Check-in Time</th>
                                        <th width="13%">Check-out Time</th>
                                        <th width="5%">Status</th>
                                        @isset($validated['range'])
                                            <th width="15%">Range</th>
                                        @endisset
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
                                                <td>{{ $attendance->attendance_date }}</td>
                                                <td>{{ $attendance->check_in_time }}</td>
                                                <td>{{ $attendance->check_out_time }}</td>
                                                <td>
                                                    @if ($attendance->check_in_time && $attendance->check_out_time)
                                                        <span class="alert-success">Complete</span>
                                                    @else
                                                        <span class="alert-danger">Incomplete</span>
                                                    @endif
                                                </td>
                                                @isset($validated['range'])
                                                <td>
                                                    @if (isset($validated['range']) && $attendance->attendance_date && $attendance->check_in_time)
                                                       @if ($attendance->range($attendance->attendance_date.' '.$attendance->check_in_time, $attendance->course->schedule, $attendance->check_in_time, $validated['range']) == 'On-time')
                                                           <span class="alert-success">On-time</span>
                                                       @else
                                                           <span class="alert-danger">Late</span>
                                                           
                                                       @endif
                                                    
                                                    @endif                                                    
                                                </td>
                                                @endisset
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
@section('scripts')
<script>    
    $(document).ready(function() {
        @if (isset($validated['course_id']) && $validated['room_id'])
        var courseId = {{ $validated['course_id'] }};
            if (courseId) {
                $.ajax({
                    url: '{{ route("get-rooms") }}',
                    type: "GET",
                    data: { course_id: courseId },
                    success: function(data) {
                        $('#room').empty();
                        $('#room').append('<option value="">Choose Room</option>');
                        $.each(data, function(key, value) {
                            $('#room').append('<option value="' + value.id + '"' + (value.id === {{ $validated['room_id'] }} ? ' selected' : '') + '>' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#room').empty();
                $('#room').append('<option value="">Choose Room</option>');
            }
    @endif
        $('#course').on('change', function() {
            var courseId = $(this).val();
            if (courseId) {
                $.ajax({
                    url: '{{ route("get-rooms") }}',
                    type: "GET",
                    data: { course_id: courseId },
                    success: function(data) {
                        $('#room').empty();
                        $('#room').append('<option value="">Choose Room</option>');
                        $.each(data, function(key, value) {
                            $('#room').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#room').empty();
                $('#room').append('<option value="">Choose Room</option>');
            }
        });
    });
</script>
@endsection

