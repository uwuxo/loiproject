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
                    <h4 class="header-title">Logged In Users</h4>
                    <div class="data-tables">
                        @if ($loggedInUsers->count())
                        <table id="dataTable" class="text-left w-100">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="10%">Total</th>
                                    <th width="50%">Login details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $total }}</td>
                                    <td>
                                @foreach ($rooms as $loggedInUser)
                                @php
                                    dd($loggedInUser->logged);
                                @endphp
                                        <table id="dataTable" class="text-left w-100">
                                            <thead class="bg-light text-capitalize">
                                            <tr>
                                            <th>Room</th>
                                            </tr>
                                            </head>
                                            <tr>
                                            <td>{{ $loggedInUser->room }}</td>
                                            </tr>
                                        </table>
                                        
                                    
                                @endforeach
                            </td>
                            </tr>
                            </tbody>
                        </table>
                        @else
                        <p>No Users Logged In</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
