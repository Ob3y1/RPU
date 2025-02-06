@extends('layouts.admin')

@section('title')
    Edit Page
@endsection
@section('content')
    <nav class="navbar bg-body-tertiary ">
            <div class="container-fluid  justify-content-md-between">
                <a class="navbar-brand fw-normal fs-4 mb-1 mb-md-0 d-none d-sm-block ">Edit Specialization</a>
            </div>
    </nav>
    <div class="container">   
        <form action="{{ route('updateinf', $specializationInfo->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="specialization_id" class="form-label">Specialization Name</label>
                    <select name="specialization_id" class="form-control" required>
                        @foreach ($allSpecializations as $specialization)
                            <option value="{{ $specialization->id }}"
                                {{ $specializationInfo->specialization_id == $specialization->id ? 'selected' : '' }}>
                                {{ $specialization->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="price_per_hour" class="form-label">Price per Hour</label>
                    <input type="number" name="price_per_hour" class="form-control" 
                        value="{{ $specializationInfo->price_per_hour }}" required>
                </div>

                <div class="mb-3">
                    <label for="num_seats" class="form-label">Number of Seats</label>
                    <input type="number" name="num_seats" class="form-control" 
                        value="{{ $specializationInfo->num_seats }}" required>
                </div>

                <div class="mb-3">
                    <label for="grant_seats" class="form-label">Grant Seats</label>
                    <input type="number" name="grant_seats" class="form-control" 
                        value="{{ $specializationInfo->grant ? $specializationInfo->grant->num_seats : 0 }}">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
