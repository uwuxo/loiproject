@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="my-4">List of Users</h1>
        <a href="{{ url('/admin/register') }}" class="btn btn-primary btn-custom">
            <i class="bi bi-person-plus"></i>New
        </a>
<!-- Hiển thị thông báo thành công -->
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    {{-- <th>ID</th> --}}
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Rooms</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        {{-- <td>{{ $user->id }}</td> --}}
                        <td><a href="{{ route('user.edit', $user->id) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <p>
                            <a href="{{ route('user.rooms', $user->id) }}">Rooms Manager</a>
                            </p>
                            @if ($user->rooms)
                            @foreach ($user->rooms as $room)
                            <p>{{ $room->name }}</p>
                            @endforeach
                            @endif
                        </td>
                        <td>
                            @if ($user->status)
                                <span class="alert-success">active</span>
                            @else
                                <span class="alert-danger">inactive</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            @if (!$user->super)
                            <button id="submitBtn{{ $user->id}}" class="btn alert-danger">Delete</button>
                            @endif
                        </td>
                    </tr>
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
                @endforeach
            </tbody>
        </table>

        <!-- Hiển thị phân trang -->
        {{ $users->links() }}
    </div>
    @endsection
