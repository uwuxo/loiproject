
@extends('backend.layouts.master')

@section('title')
Room Logged - Admin Panel
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
                <h4 class="page-title pull-left">Room Logged</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('logged') }}">Logged In</a></li>
                    <li><span>{{ $room->name }}</span></li>
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
                    <h4 class="header-title float-left">Rooms Logged List</h4>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table class="table w-100%">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="30%">Name</th>
                                    <th width="10%">Total</th>
                                    <th width="50%">Logged Users</th>
                                </tr>
                            </thead>
                            <tbody>
                               <tr>
                                    <td>{{ $room->name }}</td>
                                    <td>
                                        @if ($room->logged->count() == 0)
                                            <span>{{ $room->logged->count() }}</span>
                                        @else
                                        <span class="alert-success">{{ $room->logged->count() }}</span>
                                        @endif
                                    </td>
                                    <td class="pt-3">
                                        @if ($room->logged->count())
                                        @foreach ($room->logged as $logged)
                                            <div class="alert-success">{{ $logged->user->name }}</div>
                                        @endforeach
                                        @endif
                                    </td>
                                    
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