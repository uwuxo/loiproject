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
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><span>Logged In</span></li>
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
                    <h4 class="header-title">Logged In Users {{ $total }}</h4>
                    <div class="data-tables">
                        <table id="dataTable" class="text-left w-100">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="30%">Courses</th>
                                    <th width="60%">Rooms</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->name }}</td>
                                    <td>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th width="30%">Room</th>
                                                    <th width="10%" style="min-width: 100px;">Total</th>
                                                    <th width="60%">Users</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($course->rooms as $room)
                                                <tr>
                                                    <td>{{ $room->name }}</td>
                                                    <td><span class="alert-success">{{ $room->logged->count() }}</span></td>
                                                    <td>
                                                        <ul>
                                                            @foreach ($room->logged as $userLogged)
                                                            <li><span class="alert-success">{{ $userLogged->user->name }}</span></li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
