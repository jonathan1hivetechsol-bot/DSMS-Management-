@extends('layouts.vertical', ['title' => 'Teachers','subTitle' => 'Manage Teachers'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">All Teachers</h4>
                <a href="{{ route('teachers.create') }}" class="btn btn-primary">Add Teacher</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Teacher ID</th>
                                <th>Subject</th>
                                <th>Qualification</th>
                                <th>Hire Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($teachers->count() > 0)
                                @foreach($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                    {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <span class="ms-2">{{ $teacher->user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $teacher->user->email }}</td>
                                    <td>{{ $teacher->teacher_id }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $teacher->subject }}</span>
                                    </td>
                                    <td>{{ $teacher->qualification }}</td>
                                    <td>{{ $teacher->hire_date ? date('M d, Y', strtotime($teacher->hire_date)) : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-info" title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No teachers found. <a href="{{ route('teachers.create') }}">Add one now</a>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
