@extends('backend.layouts.master')

@section('title')
    Courses - Admin Panel
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
                    <h4 class="page-title pull-left">Courses</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><span>All Courses</span></li>
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
                        <h4 class="header-title float-left">Course List</h4>
                        <p class="float-right mb-2">
                            @hasanyrole('super-admin|group-admin')
                                <a class="btn btn-primary text-white" href="{{ route('group.create') }}">Create New Course</a>
                            @endhasanyrole
                        </p>
                        <div class="clearfix"></div>
                        <div class="data-tables">
                            @include('backend.layouts.partials.messages')
                            <table>
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th width="10%">Name</th>
                                        <th width="20%">Rooms</th>
                                        <th width="10%">Start date</th>
                                        <th width="10%">End date</th>
                                        <th width="5%">Status</th>
                                        <th width="10%">Created At</th>
                                        @hasanyrole('super-admin|group-admin|group-edit')
                                            <th width="10%">Action</th>
                                        @endhasanyrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groups as $group)
                                        <tr>
                                            <td>{{ $group->name }}</td>
                                            <td>
                                                @if ($group->rooms)
                                                    @foreach ($group->rooms as $room)
                                                        <p>{{ $room->name }}</p>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                {{ Carbon\Carbon::parse($group->start_date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ Carbon\Carbon::parse($group->end_date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                @if ($group->status)
                                                    <span class="badge badge-success">active</span>
                                                @else
                                                    <span class="badge badge-danger">inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $group->created_at->format('d-m-Y H:i:s') }}</td>
                                            @hasanyrole('super-admin|group-admin|group-edit')
                                                <td>
                                                    <a class="btn btn-success text-white"
                                                        href="{{ route('group.edit', $group->id) }}">Edit</a>
                                                    @hasanyrole('super-admin|group-admin')
                                                        <a class="btn btn-danger text-white" href="#"
                                                            id="submitBtn{{ $group->id }}">
                                                            Delete
                                                        </a>
                                                        <script>
                                                            document.getElementById('submitBtn{{ $group->id }}').addEventListener('click', function(e) {
                                                                e.preventDefault();
                                                                if (confirm('You sure you wanna trash this?')) {
                                                                    var form = document.createElement('form');
                                                                    form.method = 'post';
                                                                    form.action = '{{ route('group.destroy', $group->id) }}'; // Thay đổi URL này
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
                                                    @endhasanyrole
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
            {{ $groups->links() }}
        </div>
    </div>
@endsection
