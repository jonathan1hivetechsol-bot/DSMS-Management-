@extends('layouts.vertical', ['title' => 'Teacher Details', 'subTitle' => $teacher->user->name])

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <div class="avatar-md mx-auto">
                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-32">
                            {{ strtoupper(substr($teacher->user->name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <h5 class="mb-1">{{ $teacher->user->name }}</h5>
                <p class="text-muted mb-3">{{ $teacher->subject }}</p>
                <div class="text-center">
                    <span class="badge bg-info">{{ $teacher->teacher_id }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Teacher Information</h5>
                    <div>
                        <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Full Name</h6>
                        <p>{{ $teacher->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Email Address</h6>
                        <p><a href="mailto:{{ $teacher->user->email }}">{{ $teacher->user->email }}</a></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Teacher ID</h6>
                        <p>{{ $teacher->teacher_id }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Subject/Department</h6>
                        <p>
                            <span class="badge bg-info">{{ $teacher->subject }}</span>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Qualification</h6>
                        <p>{{ $teacher->qualification }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Hire Date</h6>
                        <p>{{ $teacher->hire_date ? date('M d, Y', strtotime($teacher->hire_date)) : 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Annual Salary</h6>
                        <p class="fs-16 fw-semibold text-success">
                            ${{ number_format($teacher->salary, 2) }}
                        </p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Joined On</h6>
                        <p>{{ $teacher->user->created_at ? date('M d, Y', strtotime($teacher->user->created_at)) : 'N/A' }}</p>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Last Updated</h6>
                        <p>{{ $teacher->updated_at ? date('M d, Y', strtotime($teacher->updated_at)) : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                        Delete Teacher
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
