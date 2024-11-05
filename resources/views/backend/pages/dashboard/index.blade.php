
@extends('backend.layouts.master')

@section('title')
Dashboard Page - Admin Panel
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Dashboard</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><span>Dashboard</span></li>
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
    <div class="col-lg-8">
        <div class="row">
            @hasanyrole('super-admin|course-admin|course-edit|course-view')
            <div class="col-md-6 mt-5 mb-3">
                <div class="card">
                    <div class="seo-fact sbg1">
                        <a href="{{ route('group.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-book"></i> Courses</div>
                                <h2>{{ $total_groups }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endhasanyrole
            @hasanyrole('super-admin|user-admin|user-edit|user-view')
            <div class="col-md-6 mt-md-5 mb-3">
                <div class="card">
                    <div class="seo-fact sbg2">
                        <a href="{{ route('users.index') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-users"></i> Users</div>
                                <h2>{{ $total_users }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endhasanyrole
            @hasanyrole('super-admin|course-admin|course-edit')
            <div class="col-md-6 mt-md-5 mb-3">
                <div class="card">
                    <div class="seo-fact sbg1">
                        <a href="{{ route('gateway') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-home"></i> Rooms</div>
                                <h2>{{ $total_rooms }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endhasanyrole
            @hasanyrole('super-admin|course-admin|course-edit|course-view|user-admin|user-edit|user-view')            
            <div class="col-md-6 mt-md-5 mb-3">
                <div class="card">
                    <div class="seo-fact sbg3">
                        <a href="{{ route('logged') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="fa fa-users"></i> Logged In Users API</div>
                                <h2>{{ $loggedInUsers }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endhasanyrole
        </div>
    </div>
  </div>
</div>
@endsection