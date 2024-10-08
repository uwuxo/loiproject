
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
                    <h4 class="header-title float-left">{{ $course->name }} - Rooms List</h4>
                    <p class="float-right mb-2">
                        <a class="btn btn-primary text-white" href="{{ route('room.add', $course->id) }}">Create New Room</a>
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table>
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="10%">Name</th>
                                    <th width="20%">Allowed days</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rooms as $room)
                               <tr>
                                    <td>{{ $room->name }}</td>
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