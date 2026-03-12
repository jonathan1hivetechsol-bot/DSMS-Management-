@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Book Loans</h1>
    <a href="{{ route('loans.create') }}" class="btn btn-primary mb-3">New Loan</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Book</th>
                <th>Student</th>
                <th>Loaned</th>
                <th>Due</th>
                <th>Returned</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->student->name }}</td>
                <td>{{ $loan->loaned_at }}</td>
                <td>{{ $loan->due_at }}</td>
                <td>{{ $loan->returned_at ?? '-' }}</td>
                <td>
                    @if(!$loan->returned_at)
                    <a href="{{ route('loans.return', $loan) }}" class="btn btn-sm btn-success">Return</a>
                    @endif
                    <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete loan?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection