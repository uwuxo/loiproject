@extends('layouts.app')
@section('content')
    <div class="container" style="margin-bottom: 20px">
        <h1 class="my-4">{{ $user->name }} Add Room</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('room.store', $user->id) }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="allowed_days" class="form-label">Allowed Login Days</label><br>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="1" id="monday">
                    <label class="form-check-label" for="monday">Monday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="2" id="tuesday">
                    <label class="form-check-label" for="tuesday">Tuesday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="3" id="wednesday">
                    <label class="form-check-label" for="wednesday">Wednesday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="4" id="thursday">
                    <label class="form-check-label" for="thursday">Thursday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="5" id="friday">
                    <label class="form-check-label" for="friday">Friday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="6" id="saturday">
                    <label class="form-check-label" for="saturday">Saturday</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="allowed_days[]" value="0" id="sunday">
                    <label class="form-check-label" for="sunday">Sunday</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add</button>
        
        </form>
    </div>

    @endsection
