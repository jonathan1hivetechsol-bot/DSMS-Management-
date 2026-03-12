@extends('layouts.vertical', ['title' => 'Student Details', 'subTitle' => $student->user->name])

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <div class="avatar-md mx-auto">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-32">
                            {{ strtoupper(substr($student->user->name, 0, 1)) }}
                        </span>
                    </div>
                </div>
                <h5 class="mb-1">{{ $student->user->name }}</h5>
                <p class="text-muted mb-3">{{ $student->schoolClass ? $student->schoolClass->name : 'N/A' }}</p>
                <div class="text-center">
                    <span class="badge bg-primary">Student ID: {{ $student->student_id }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Student Information</h5>
                    <div>
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('students.index') }}" class="btn btn-sm btn-secondary">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Full Name</h6>
                        <p>{{ $student->user->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Email Address</h6>
                        <p>{{ $student->user->email }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Student ID</h6>
                        <p>{{ $student->student_id }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Date of Birth</h6>
                        <p>{{ $student->date_of_birth ? date('M d, Y', strtotime($student->date_of_birth)) : 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Class</h6>
                        <p>{{ $student->schoolClass ? $student->schoolClass->name : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Phone Number</h6>
                        <p>{{ $student->phone ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <h6 class="text-muted mb-1">Address</h6>
                        <p>{{ $student->address ?? 'N/A' }}</p>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Parent/Guardian Information</h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Parent/Guardian Name</h6>
                        <p>{{ $student->parent_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Parent Phone Number</h6>
                        <p>{{ $student->parent_phone }}</p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Member Since</h6>
                        <p>{{ $student->user->created_at ? date('M d, Y', strtotime($student->user->created_at)) : 'N/A' }}</p>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Last Updated</h6>
                        <p>{{ $student->updated_at ? date('M d, Y', strtotime($student->updated_at)) : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('students.report', $student) }}" class="btn btn-info">View Report Card</a>
                <a href="{{ route('students.report.pdf', $student) }}" class="btn btn-secondary">Download PDF</a>
                <a href="{{ route('invoices.index', ['student_id'=>$student->id]) }}" class="btn btn-warning">Invoices</a>
                <a href="{{ route('loans.index', ['student_id'=>$student->id] ?? []) }}" class="btn btn-info">Loans</a>
                <form action="{{ route('students.destroy', $student) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                        Delete Student
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
