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
                    <h4 class="header-title">Logged In Users {{ $loggeds }}</h4>
                    <div class="data-tables">
                        
                                        <table class="table w-100%">
                                            <thead>
                                                <tr>
                                                    <th width="30%">Room</th>
                                                    <th width="10%">Total</th>
                                                    <th width="60%">Users</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($rooms)
                                                @foreach ($rooms as $room)
                                                <tr>
                                                    <td>{{ $room->name }}</td>
                                                    <td>
                                                        @if ($room->logged->count() == 0)
                                                            <span>{{ $room->logged->count() }}</span>
                                                        @else
                                                        <span class="alert-success">{{ $room->logged->count() }}</span></td>
                                                        @endif
                                                        
                                                    <td>
                                                        <ul>
                                                            @foreach ($room->logged as $userLogged)
                                                            <li><span class="alert-success">{{ $userLogged->user->name }}</span></li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="3" class="text-center">No rooms found</td>
                                                </tr>
                                                @endif
                                                
                                            </tbody>
                                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{ $rooms->links() }}
    </div>
</div>
@endsection
