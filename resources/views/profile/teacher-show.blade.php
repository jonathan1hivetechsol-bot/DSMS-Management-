@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-10 offset-lg-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Teacher Profile - {{ $teacher->user->name }}</h2>
            <div>
                @can('update', $teacher)
                    <a href="{{ route('profile.teacher.edit', $teacher->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endcan
                <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-secondary btn-sm">Back</a>
            </div>
        </div>

        <!-- Teacher Header Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        @if($teacher->user->avatar)
                            <img src="{{ asset('storage/' . $teacher->user->avatar) }}" alt="{{ $teacher->user->name }}" 
                                 class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" 
                                 style="width: 150px; height: 150px; margin: 0 auto; font-size: 48px;">
                                {{ strtoupper(substr($teacher->user->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h4>{{ $teacher->user->name }}</h4>
                        <small class="text-muted">Teacher</small>
                        <div class="mt-3">
                            <span class="badge bg-primary">{{ $teacher->subject }}</span>
                            <span class="badge bg-{{ $teacher->employment_status === 'permanent' ? 'success' : 'warning' }}">
                                {{ ucfirst($teacher->employment_status) }}
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
                        <p class="fw-bold">{{ $teacher->user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Gender</small>
                        <p class="fw-bold">{{ ucfirst($teacher->user->gender ?? 'N/A') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Date of Birth</small>
                        <p class="fw-bold">{{ $teacher->user->date_of_birth ? $teacher->user->date_of_birth->format('d M, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">CNIC</small>
                        <p class="fw-bold">{{ $teacher->cnic ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Professional Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Teacher ID</small>
                        <p class="fw-bold">{{ $teacher->teacher_id }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Subject</small>
                        <p class="fw-bold">{{ $teacher->subject }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Qualification</small>
                        <p class="fw-bold">{{ $teacher->qualification }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Specialization</small>
                        <p class="fw-bold">{{ $teacher->specialization ?? 'General' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Years of Experience</small>
                        <p class="fw-bold">{{ $teacher->years_of_experience ?? 0 }} years</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Employment Status</small>
                        <p class="fw-bold">
                            <span class="badge bg-{{ $teacher->employment_status === 'permanent' ? 'success' : ($teacher->employment_status === 'contract' ? 'warning' : 'danger') }}">
                                {{ ucfirst($teacher->employment_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Education & Qualifications -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Education & Qualifications</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <small class="text-muted">Qualifications</small>
                        <p class="fw-bold">{{ $teacher->qualifications ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-muted">Previous Schools / Experience</small>
                        <p class="fw-bold">{{ $teacher->previous_schools ?? 'None mentioned' }}</p>
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
                        <p class="fw-bold">{{ $teacher->user->phone ?? $teacher->phone ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Email</small>
                        <p class="fw-bold">{{ $teacher->user->email }}</p>
                    </div>
                    <div class="col-12 mb-3">
                        <small class="text-muted">Address</small>
                        <p class="fw-bold">{{ $teacher->user->full_address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Information -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Salary Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Base Salary</small>
                        <p class="fw-bold">Rs. {{ number_format($teacher->salary, 2) }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Salary Review Date</small>
                        <p class="fw-bold">{{ $teacher->salary_review_date ? $teacher->salary_review_date->format('d M, Y') : 'Not scheduled' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Employment Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Hire Date</small>
                        <p class="fw-bold">{{ $teacher->hire_date ? $teacher->hire_date->format('d M, Y') : 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Document Verified</small>
                        <p class="fw-bold">
                            @if($teacher->document_verified_at)
                                <span class="badge bg-success">✓ Verified</span> - {{ $teacher->document_verified_at->format('d M, Y') }}
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teaching Approach -->
        @if($teacher->teaching_approach)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Teaching Approach & Philosophy</h6>
                </div>
                <div class="card-body">
                    <p>{{ $teacher->teaching_approach }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
