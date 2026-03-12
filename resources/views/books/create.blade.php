@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Book</h1>
    <form action="{{ route('books.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Author</label>
            <input type="text" name="author" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input type="text" name="isbn" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Copies</label>
            <input type="number" name="copies" class="form-control" value="1" min="0">
        </div>
        <button class="btn btn-primary">Save</button>
    </form>
</div>
@endsection