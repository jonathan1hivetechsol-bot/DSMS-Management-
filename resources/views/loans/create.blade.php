@extends('layouts.app')

@section('content')
<div class="container">
    <h1>New Loan</h1>
    <form action="{{ route('loans.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Book</label>
            <select name="book_id" class="form-control" required>
                <option value="">-- select --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}">{{ $book->title }} ({{ $book->copies }} copies)</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-control" required>
                <option value="">-- select --</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Loaned At</label>
            <input type="date" name="loaned_at" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Due At</label>
            <input type="date" name="due_at" class="form-control" required>
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection