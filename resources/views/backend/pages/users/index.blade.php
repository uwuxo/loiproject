
@extends('backend.layouts.master')

@section('title')
Users - Admin Panel
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
                <h4 class="page-title pull-left">Users</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><span>All Users</span></li>
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
                    <h4 class="header-title float-left">Users List</h4>
                    <p class="float-right mb-2">
                        <a class="btn btn-primary text-white" href="{{ route('user.register') }}">Create New User</a>
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table>
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="10%">Name</th>
                                    <th width="10%">Username</th>
                                    <th width="10%">Email</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Created At</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($users as $user)
                               <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->status)
                                            <span class="alert-success">active</span>
                                        @else
                                            <span class="alert-danger">inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                        <a class="btn btn-success text-white" href="{{ route('user.edit', $user->id) }}">Edit</a>
                                        @if (!$user->super)
                                        <a class="btn btn-danger text-white" href="#" id="submitBtn{{ $user->id}}">
                                            Delete
                                        </a>

                                        @if (!$user->super)
                                        <script>
                                            document.getElementById('submitBtn{{ $user->id}}').addEventListener('click', function(e) {
                                                e.preventDefault();
                                                if (confirm('You sure you wanna trash this?')) {
                                                    var form = document.createElement('form');
                                                    form.method = 'post';
                                                    form.action = '{{ route('user.destroy', $user->id) }}'; // Thay đổi URL này
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
                                        @endif
                                        @endif
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
        {{ $users->links() }}
    </div>
</div>
@endsection
