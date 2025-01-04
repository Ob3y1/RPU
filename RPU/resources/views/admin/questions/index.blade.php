@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Support Questions</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Status</th>
                <th>Frequent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
                <tr>
                    <td>{{ $question->id }}</td>
                    <td>{{ $question->question }}</td>
                    <td>
                        @if($question->answer)
                            <span class="badge bg-success">Answered</span>
                        @else
                            <span class="badge bg-warning text-dark">Not Answered</span>
                        @endif
                    </td>
                    <td>
                        @if($question->is_frequent)
                            <span class="badge bg-success">Frequent</span>
                        @else
                            <span class="badge bg-secondary">Not Frequent</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex">
                            <!-- زر تسجيل الرد -->
                            <a href="{{ route('admin.questions.answerForm', $question->id) }}" class="btn btn-info btn-sm me-2">Reply</a>

                            <!-- زر تمييز السؤال كشائع -->
                            <form action="{{ route('questions.toggleFrequent', $question->id) }}" method="POST" class="me-2">
                                @csrf
                                @if($question->is_frequent)
                                    <button type="submit" class="btn btn-danger btn-sm">Remove from Frequent</button>
                                @else
                                    <button type="submit" class="btn btn-primary btn-sm">Mark as Frequent</button>
                                @endif
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $questions->links() }}
    </div>
</div>
@endsection
