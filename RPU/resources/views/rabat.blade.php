@extends('layouts.admin')

@section('title')
    Universities Management Page
@endsection

@section('content')
    <div class="container ">
        <div class="page-inner">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <h3 class="ms-2 mt-2">Universities</h3>
            <hr> 
            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row ">
                                <div class="card-title">University</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Details</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($universities as $university)
                                        <tr>
                                            <td>{{ $university->id }}</td>
                                            <td>{{ $university->name }}</td>
                                            <td>{{ $university->location }}</td>
                                            <td>{{ $university->details }}</td>
                                            <td>
                                                <a href="{{ route('edituni', $university->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('deleteuni', $university->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                                <a href="{{ route('infouni', $university->id) }}" class="btn btn-success btn-sm">Information</a>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Add University</div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <form action="{{ route('adduni') }}" method="POST" class="mb-2">
                                @csrf
                                <div class="mb-2">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label for="details" class="form-label">Details</label>
                                    <textarea name="details" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Save</button>
                            </form> 
                        </div>
                    </div>
                    
                </div>                
            </div>
            <hr> 
            <h3 class="ms-2">Specialization</h3>
            <hr>    
            <div class="row mt-4 mb-4 ">
                
                <div class="col-md-8">
                     
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title"> Specialization</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                        <table class="table mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Details</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($specialization as $specialization)
                                        <tr>
                                            <td>{{ $specialization->id }}</td>
                                            <td>{{ $specialization->name }}</td>
                                            <td>{{ $specialization->details }}</td>
                                            <td>
                                                <a href="{{ route('editspe', $specialization->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('deletespe', $specialization->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Add Specialization </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <form action="{{ route('addspe') }}" method="POST" class="mb-2">
                                @csrf
                                <div class="mb-2">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label for="details" class="form-label">Details</label>
                                    <textarea name="details" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Save</button>
                            </form> 
                        </div>
                    </div>



            </div> 
        </div>
    </div>   
@endsection
