@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <!-- Avatar -->
                <div class="mb-3">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                             class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px; margin: 0 auto; font-size: 48px;">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <h3 class="card-title mb-1">{{ $user->name }}</h3>
                <p class="text-muted mb-3">{{ ucfirst($user->role) }}</p>
                
                @if($user->email)
                    <p class="mb-2"><small class="text-muted">{{ $user->email }}</small></p>
                @endif
                
                @if($user->phone)
                    <p class="mb-2"><small class="text-muted">📞 {{ $user->phone }}</small></p>
                @endif

                <!-- Profile Completion -->
                @if($user->profile_completed_at)
                    <div class="alert alert-success mb-3" role="alert">
                        <small>✓ Profile Completed</small>
                    </div>
                @else
                    <div class="alert alert-warning mb-3" role="alert">
                        <small>⚠ Complete Your Profile</small>
                    </div>
                @endif

                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>

        <!-- Contact Information Card -->
        <div class="card border-0 shadow-sm mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Contact Information</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted"><small>Phone:</small></td>
                        <td><small>{{ $user->phone ?? 'Not provided' }}</small></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><small>Email:</small></td>
                        <td><small>{{ $user->email }}</small></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><small>City:</small></td>
                        <td><small>{{ $user->city ?? 'Not provided' }}</small></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><small>Country:</small></td>
                        <td><small>{{ $user->country ?? 'Not provided' }}</small></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Main Profile Details -->
    <div class="col-lg-8">
        <!-- Personal Information -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Personal Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Full Name</small>
                        <p>{{ $user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Gender</small>
                        <p>{{ ucfirst($user->gender ?? 'Not provided') }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Date of Birth</small>
                        <p>{{ $user->date_of_birth ? $user->date_of_birth->format('d M, Y') : 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">CNIC</small>
                        <p>{{ $user->cnic ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Address</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">City</small>
                        <p>{{ $user->city ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Country</small>
                        <p>{{ $user->country ?? 'Pakistan' }}</p>
                    </div>
                    <div class="col-12 mb-2">
                        <small class="text-muted">Full Address</small>
                        <p>{{ $user->full_address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Emergency Contact</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Contact Name</small>
                        <p>{{ $user->emergency_contact ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">Contact Phone</small>
                        <p>{{ $user->emergency_phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bio -->
        @if($user->bio)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Bio</h6>
                </div>
                <div class="card-body">
                    <p>{{ $user->bio }}</p>
                </div>
            </div>
        @endif

        <!-- Role-Specific Profile -->
        @if($profileType === 'student' && $profile)
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Student Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Student ID</small>
                            <p>{{ $profile->student_id }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Registration Number</small>
                            <p>{{ $profile->registration_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Roll Number</small>
                            <p>{{ $profile->roll_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Blood Group</small>
                            <p>{{ $profile->blood_group ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Class</small>
                            <p>{{ $profile->schoolClass ? $profile->schoolClass->name : 'Not assigned' }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Admission Status</small>
                            <p>
                                <span class="badge bg-{{ $profile->admission_status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($profile->admission_status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('profile.student.edit', $profile->id) }}" class="btn btn-sm btn-primary">
                        Edit Student Details
                    </a>
                </div>
            </div>
        @elseif($profileType === 'teacher' && $profile)
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Teacher Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Teacher ID</small>
                            <p>{{ $profile->teacher_id }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Subject</small>
                            <p>{{ $profile->subject }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Qualification</small>
                            <p>{{ $profile->qualification }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Specialization</small>
                            <p>{{ $profile->specialization ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Years of Experience</small>
                            <p>{{ $profile->years_of_experience ?? 0 }} years</p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted">Employment Status</small>
                            <p>
                                <span class="badge bg-{{ $profile->employment_status === 'permanent' ? 'success' : 'warning' }}">
                                    {{ ucfirst($profile->employment_status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('profile.teacher.edit', $profile->id) }}" class="btn btn-sm btn-primary">
                        Edit Teacher Details
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
