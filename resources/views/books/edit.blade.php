@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Book</h1>
    <form action="{{ route('books.update', $book) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ $book->title }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Author</label>
            <input type="text" name="author" class="form-control" value="{{ $book->author }}">
        </div>
        <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control" value="{{ $book->isbn }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Copies</label>
            <input type="number" name="copies" class="form-control" value="{{ $book->copies }}" min="0">
        </div>
        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection