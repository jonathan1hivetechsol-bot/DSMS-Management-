@extends('layouts.vertical', ['title' => 'Subjects', 'subTitle' => 'Manage Subjects'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Subjects</h4>
                <a href="{{ route('subjects.create') }}" class="btn btn-primary">Add Subject</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjects as $subject)
                                <tr>
                                    <td>{{ $subject->name }}</td>
                                    <td>{{ $subject->code }}</td>
                                    <td>{{ Str::limit($subject->description, 50) }}</td>
                                    <td>
                                        <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subject?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No subjects added.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $subjects->links() }}
            </div>
        </div>
    </div>
</div>
@endsection