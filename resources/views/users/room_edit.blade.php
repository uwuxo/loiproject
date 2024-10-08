@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="my-4">Edit Room</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('room.update', $room->id) }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $room->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="allowed_days" class="form-label">Allowed Login Days</label><br>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="1" id="monday" {{ in_array(1, $allowed_days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="monday">Monday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="2" id="tuesday" {{ in_array(2, $allowed_days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="tuesday">Tuesday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="3" id="wednesday" {{ in_array(3, $allowed_days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="wednesday">Wednesday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="4" id="thursday" {{ in_array(4, $allowed_days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="thursday">Thursday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="5" id="friday" {{ in_array(5, $allowed_days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="friday">Friday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="6" id="saturday" {{ in_array(6, $allowed_days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="saturday">Saturday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="0" id="sunday" {{ in_array(0, $allowed_days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="sunday">Sunday</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Room</button>
        </form>
    </div>

    @endsection
