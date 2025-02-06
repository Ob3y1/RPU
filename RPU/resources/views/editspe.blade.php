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
        <form action="{{ route('updatespe', $specialization->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $specialization->name }}" required>
            </div>

            <div class="mb-3">
                <label for="details" class="form-label">Details</label>
                <textarea name="details" class="form-control">{{ $specialization->details }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
