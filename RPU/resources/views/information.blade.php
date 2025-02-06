@extends('layouts.admin')

@section('title')
    Information Page
@endsection

@section('content')
    <nav class="navbar bg-body-tertiary ">
        <div class="container-fluid  justify-content-md-between">
            <a class="navbar-brand fw-normal fs-4 mb-1 mb-md-0 d-none d-sm-block ">Information for {{ $university->name }}</a>
        </div>
    </nav>
    <div class="container ">
        <div class="page-inner">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

 
            <div class="row mt-4 mb-4 ">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row ">
                                <div class="card-title">Specializations IN {{ $university->name }}</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Specialization</th>
                                        <th>Price Per Hour</th>
                                        <th>Number of Seats</th>
                                        <th>Grant Available</th>
                                        <th>Grant Seats</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($specializations as $index => $specialization)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $specialization->specialization->name }}</td>
                                            <td>{{ $specialization->price_per_hour }}</td>
                                            <td>{{ $specialization->num_seats }}</td>
                                            <td>{{ $specialization->grant ? 'Yes' : 'No' }}</td>
                                            <td>{{ $specialization->grant ? $specialization->grant->num_seats : 'No Grant' }}</td>
                                            <td>
                                                <a href="{{ route('editinf', $specialization->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('deleteinf', $specialization->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No specializations found for this university.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Add Specialization in this University</div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <form action="{{ route('addinf') }}" method="POST">
                                @csrf
                                <input type="hidden" name="university_id" value="{{ $university->id }}">
                                
                                <div class="mb-3">
                                    <label for="specialization_id" class="form-label">Specialization</label>
                                    <select name="specialization_id" class="form-select" required>
                                        <option value="">Select a Specialization</option>
                                        @foreach ($allspecializations as $specialization)
                                            <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="price_per_hour" class="form-label">Price per Hour</label>
                                    <input type="number" name="price_per_hour" class="form-control" placeholder="Enter price per hour" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="num_seats" class="form-label">Number of Seats</label>
                                    <input type="number" name="num_seats" class="form-control" placeholder="Enter number of seats" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="grant" class="form-label">Grant Available?</label>
                                    <select name="grant" id="grant-select" class="form-select" required>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3 d-none" id="grant-seats-field">
                                    <label for="grant_seats" class="form-label">Number of Grant Seats</label>
                                    <input type="number" name="grant_seats" class="form-control" placeholder="Enter number of grant seats">
                                </div>
                                
                                <button type="submit" class="btn btn-success w-100">Save Specialization</button>
                            </form> 
                        </div>
                    </div>
                    
                </div>                
            </div>
               
 
        </div>
    </div>   
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const grantSelect = document.getElementById('grant-select');
        const grantSeatsField = document.getElementById('grant-seats-field');

        grantSelect.addEventListener('change', function () {
            if (this.value === '1') {
                grantSeatsField.classList.remove('d-none');  // Show the field
                grantSeatsField.querySelector('input').setAttribute('required', 'required');
            } else {
                grantSeatsField.classList.add('d-none');  // Hide the field
                grantSeatsField.querySelector('input').removeAttribute('required');
            }
        });
    });
</script>