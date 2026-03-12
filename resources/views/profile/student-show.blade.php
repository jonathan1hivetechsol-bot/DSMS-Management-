@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Student Profile - {{ $student->user->name }}</h2>
            <div>
                @can('update', $student)
                    <a href="{{ route('profile.student.edit', $student->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endcan
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>

        <!-- Student Header Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        @if($student->user->avatar)
                            <img src="{{ asset('storage/' . $student->user->avatar) }}" alt="{{ $student->user->name }}" 
                                 class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px; margin: 0 auto; font-size: 48px;">
                                {{ strtoupper(substr($student->user->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h4>{{ $student->user->name }}</h4>
                        <small class="text-muted">Student</small>
                        <div class="mt-3">
                            <span class="badge bg-{{ $student->admission_status === 'active' ? 'success' : 'secondary' }}">
                                {{ ucfirst($student->admission_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Details -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Personal Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Full Name</small>
                        <p class="fw-bold">{{ $student->user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Date of Birth</small>
                        <p class="fw-bold">{{ $student->user->date_of_birth ? $student->user->date_of_birth->format('d M, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Gender</small>
                        <p class="fw-bold">{{ ucfirst($student->user->gender ?? 'N/A') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Blood Group</small>
                        <p class="fw-bold">{{ $student->blood_group ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Identification -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Student Identification</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Student ID</small>
                        <p class="fw-bold">{{ $student->student_id }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Registration Number</small>
                        <p class="fw-bold">{{ $student->registration_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Roll Number</small>
                        <p class="fw-bold">{{ $student->roll_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Class</small>
                        <p class="fw-bold">{{ $student->schoolClass ? $student->schoolClass->name : 'Not assigned' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Admission Date</small>
                        <p class="fw-bold">{{ $student->admission_date ? $student->admission_date->format('d M, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Status</small>
                        <p class="fw-bold">
                            <span class="badge bg-{{ $student->admission_status === 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($student->admission_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Contact Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Phone</small>
                        <p class="fw-bold">{{ $student->phone ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Email</small>
                        <p class="fw-bold">{{ $student->user->email }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-muted">Address</small>
                        <p class="fw-bold">{{ $student->user->full_address ?? $student->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guardian Information -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Guardian Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Guardian Name</small>
                        <p class="fw-bold">{{ $student->parent_name ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Guardian Phone</small>
                        <p class="fw-bold">{{ $student->parent_phone ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Guardian CNIC</small>
                        <p class="fw-bold">{{ $student->guardian_cnic ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Occupation</small>
                        <p class="fw-bold">{{ $student->guardian_occupation ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Alternative Phone</small>
                        <p class="fw-bold">{{ $student->guardian_phone_alt ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical & Academic Information -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Medical & Academic Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <small class="text-muted">Medical Conditions</small>
                        <p class="fw-bold">{{ $student->medical_conditions ?? 'None reported' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-muted">Previous School</small>
                        <p class="fw-bold">{{ $student->previous_school ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Remarks</small>
                        <p class="fw-bold">{{ $student->remarks ?? 'None' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
