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
                    @if (auth()->user()->type == 'teacher')
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @endif
                    <li><span>{{ $user->type }}</span></li>
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
                    <h4 class="header-title">Courses</h4>
                    <div class="data-tables">
                        @if (!empty($user->courses->count()))
                        <table id="dataTable" class="text-left w-100">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="30%">Courses</th>
                                    <th width="15%">Start date</th>
                                    <th width="15%">End date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->courses as $course)
                                <tr>
                                    <td><a href="{{ route('detail', $course->id) }}">{{ $course->name }}</a></td>
                                    <td>{{ Carbon\Carbon::parse($course->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($course->end_date)->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p>No courses</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
