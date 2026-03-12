@extends('layouts.vertical', ['title' => 'Class Details', 'subTitle' => $class->name])

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <div class="avatar-lg mx-auto">
                        <span class="avatar-title bg-success-subtle text-success rounded-circle fs-32">
                            <i class="ri-book-line"></i>
                        </span>
                    </div>
                </div>
                <h5 class="mb-1">{{ $class->name }}</h5>
                <p class="text-muted mb-3">School Class</p>
                <div class="text-center">
                    <span class="badge bg-success">{{ $class->students->count() }} Students</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Occupancy</h5>
            </div>
            <div class="card-body">
                @php
                    $occupancy = ($class->students->count() / $class->capacity) * 100;
                @endphp
                <div class="progress mb-3" style="height: 25px;">
                    <div class="progress-bar @if($occupancy > 90) bg-danger @elseif($occupancy > 70) bg-warning @else bg-success @endif" 
                        role="progressbar" style="width: {{ $occupancy }}%; line-height: 25px;" 
                        aria-valuenow="{{ $occupancy }}" aria-valuemin="0" aria-valuemax="100">
                        {{ round($occupancy) }}%
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-muted mb-1">Current</h6>
                        <p class="fs-18 fw-bold text-primary">{{ $class->students->count() }}</p>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted mb-1">Capacity</h6>
                        <p class="fs-18 fw-bold">{{ $class->capacity }}</p>
                    </div>
                </div>
                <p class="text-muted text-center mb-0 mt-3">
                    <small>{{ $class->capacity - $class->students->count() }} seats available</small>
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Class Information</h5>
                    <div>
                        <a href="{{ route('classes.edit', $class) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('classes.index') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Class Name</h6>
                        <p class="fs-16 fw-semibold">{{ $class->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Class Capacity</h6>
                        <p class="fs-16 fw-semibold">{{ $class->capacity }} Students</p>
                    </div>
                </div>

                <hr class="my-3">

                <h5 class="mb-3">Class Teacher</h5>
                @if($class->teacher)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm me-2">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle">
                                {{ strtoupper(substr($class->teacher->user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold">{{ $class->teacher->user->name }}</p>
                            <p class="text-muted mb-0">{{ $class->teacher->subject }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-muted">
                        <span class="badge bg-secondary">Not Assigned</span>
                    </p>
                @endif

                <hr class="my-3">

                <h5 class="mb-3">Students ({{ $class->students->count() }})</h5>
                @if($class->students->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Student ID</th>
                                    <th>DOB</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($class->students as $student)
                                <tr>
                                    <td>{{ $student->id }}</td>
                                    <td>
                                        <a href="{{ route('students.show', $student) }}" class="text-decoration-none">
                                            {{ $student->user->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $student->student_id }}</span>
                                    </td>
                                    <td>{{ $student->date_of_birth ? date('M d, Y', strtotime($student->date_of_birth)) : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No students enrolled in this class yet.</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('classes.destroy', $class) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                        Delete Class
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
