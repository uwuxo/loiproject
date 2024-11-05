
@extends('backend.layouts.master')

@section('title')
Room - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Rooms</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('group.index') }}">Courses</a></li>
                    <li><span>All Rooms</span></li>
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
                    <h4 class="header-title float-left">Rooms List</h4>
                    <p class="float-right mb-2">
                        @hasanyrole('super-admin|group-admin')
                        <a class="btn btn-primary text-white" href="{{ route('room.add') }}">Create New Room</a>
                        @endhasanyrole
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table>
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="30%">Name</th>
                                    <th width="50%">Courses</th>
                                    @hasanyrole('super-admin|group-admin')
                                    <th width="15%">Action</th>
                                    @endhasanyrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $room)
                               <tr>
                                    <td>{{ $room->name }}</td>
                                    <td class="pt-3">
                                        @if ($room->courses)
                                        @foreach ($room->courses as $course)
                                        
                                            <div class="title">{{ $course->name }}</div>
                                            <div class="title">Schedule<br>Start: {{ Carbon\Carbon::parse($course->start_date)->format('d/m/Y') }} - End: {{ Carbon\Carbon::parse($course->end_date)->format('d/m/Y') }}</div>
                                            <div class="weekly-schedule mb-5">
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
                                                        <div class="pb-2">
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
                                                </div>
                                        
                                        @endforeach
                                        @endif
                                    </td>
                                    @hasanyrole('super-admin|group-admin')
                                    <td>
                                        <a class="btn btn-success text-white" href="{{ route('room.edit', $room->id) }}">Edit</a>
                                        
                                        <a class="btn btn-danger text-white" href="#" id="submitBtn{{ $room->id}}">
                                            Delete
                                        </a>
                                        <script>
                                            document.getElementById('submitBtn{{ $room->id}}').addEventListener('click', function(e) {
                                                e.preventDefault();
                                                if (confirm('You sure you wanna trash this?')) {
                                                    var form = document.createElement('form');
                                                    form.method = 'post';
                                                    form.action = '{{ route('room.destroy', $room->id) }}'; // Thay đổi URL này
                                                    // Thêm CSRF token vào form
                                                    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                                    var csrfInput = document.createElement('input');
                                                    csrfInput.type = 'hidden';
                                                    csrfInput.name = '_token';
                                                    csrfInput.value = csrfToken;
                                                    form.appendChild(csrfInput);
                                                    document.body.appendChild(form);
                                                    form.submit();
                                                }
                                            });
                                        </script>
                                    </td>
                                    @endhasanyrole
                                </tr>
                               @endforeach
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