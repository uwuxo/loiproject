@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="my-4">{{ $user->name }}'s Rooms List</h1>
        <a href="{{ route('room.add', $user->id) }}" class="btn btn-primary btn-custom">
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
                    <th>Allowed days</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr>
                        {{-- <td>{{ $room->id }}</td> --}}
                        <td>
                            <a href="{{ route('room.edit', $room->id) }}">
                            {{ $room->name }}
                            </a>
                        </td>
                        <td>
                            @php
                            $allowed_days = [];
                            $allowed_days = json_decode($room->allowed_days, true);
                            @endphp
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
                        </td>
                        <td>
                            <button id="submitBtn{{ $room->id}}" class="btn alert-danger">Delete</button>
                        </td>
                    </tr>
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
                    
                @endforeach
            </tbody>
        </table>
    </div>
    
    @endsection
