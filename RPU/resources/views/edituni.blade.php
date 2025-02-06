@extends('layouts.admin')

@section('title')
    Update Page
@endsection


@section('content')
        <h1>Edit University</h1>
        <form action="{{ route('updateuni', $university->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $university->name }}" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="{{ $university->location }}" required>
            </div>
            <div class="mb-3">
                <label for="details" class="form-label">Details</label>
                <textarea name="details" class="form-control">{{ $university->details }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
@endsection
