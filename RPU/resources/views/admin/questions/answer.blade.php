@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Answer Question</h1>
    <form method="POST" action="{{ route('admin.questions.answer', $question->id) }}">
        @csrf
        <div class="mb-3">
            <label for="question" class="form-label">Question</label>
            <textarea class="form-control" id="question" rows="3" readonly>{{ $question->question }}</textarea>
        </div>
        <div class="mb-3">
            <label for="answer" class="form-label">Answer</label>
            <textarea name="answer" class="form-control" id="answer" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Answer</button>
    </form>
</div>
@endsection
