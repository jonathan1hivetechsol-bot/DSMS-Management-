@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Teacher Profile - {{ $teacher->user->name }}</h5>
                <a href="{{ route('profile.teacher.show', $teacher->id) }}" class="btn btn-sm btn-link">Back</a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.teacher.update', $teacher->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Professional Information -->
                    <h6 class="mb-3">Professional Information</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="teacher_id" class="form-label">Teacher ID</label>
                            <input type="text" class="form-control" id="teacher_id" value="{{ $teacher->teacher_id }}" disabled>
                            <small class="text-muted">Cannot be changed</small>
                        </div>
                        <div class="col-md-6">
                            <label for="subject" class="form-label">Subject*</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject', $teacher->subject) }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="qualification" class="form-label">Primary Qualification*</label>
                            <input type="text" class="form-control @error('qualification') is-invalid @enderror" 
                                   id="qualification" name="qualification" value="{{ old('qualification', $teacher->qualification) }}" placeholder="e.g., B.Sc, M.Sc" required>
                            @error('qualification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                   id="specialization" name="specialization" value="{{ old('specialization', $teacher->specialization) }}" placeholder="e.g., Advanced Mathematics">
                            @error('specialization')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="years_of_experience" class="form-label">Years of Experience</label>
                            <input type="number" class="form-control @error('years_of_experience') is-invalid @enderror" 
                                   id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience', $teacher->years_of_experience ?? 0) }}" min="0">
                            @error('years_of_experience')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="employment_status" class="form-label">Employment Status*</label>
                            <select class="form-select @error('employment_status') is-invalid @enderror" 
                                    id="employment_status" name="employment_status" required>
                                <option value="permanent" {{ old('employment_status', $teacher->employment_status) === 'permanent' ? 'selected' : '' }}>Permanent</option>
                                <option value="contract" {{ old('employment_status', $teacher->employment_status) === 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="temporary" {{ old('employment_status', $teacher->employment_status) === 'temporary' ? 'selected' : '' }}>Temporary</option>
                            </select>
                            @error('employment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Education Details -->
                    <h6 class="mb-3">Education & Qualifications</h6>

                    <div class="mb-3">
                        <label for="qualifications" class="form-label">Additional Qualifications</label>
                        <textarea class="form-control @error('qualifications') is-invalid @enderror" 
                                  id="qualifications" name="qualifications" rows="3" placeholder="List all degrees, certifications, courses, etc.">{{ old('qualifications', $teacher->qualifications) }}</textarea>
                        <small class="text-muted">e.g., B.A English, M.Ed, TESOL Certificate</small>
                        @error('qualifications')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="previous_schools" class="form-label">Previous Schools / Work Experience</label>
                        <textarea class="form-control @error('previous_schools') is-invalid @enderror" 
                                  id="previous_schools" name="previous_schools" rows="3" placeholder="List previous schools and positions held">{{ old('previous_schools', $teacher->previous_schools) }}</textarea>
                        @error('previous_schools')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="teaching_approach" class="form-label">Teaching Approach & Philosophy</label>
                        <textarea class="form-control @error('teaching_approach') is-invalid @enderror" 
                                  id="teaching_approach" name="teaching_approach" rows="3" placeholder="Describe your teaching methodology and educational philosophy">{{ old('teaching_approach', $teacher->teaching_approach) }}</textarea>
                        @error('teaching_approach')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <!-- Contact & Personal Information -->
                    <h6 class="mb-3">Contact & Personal Information</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cnic" class="form-label">CNIC</label>
                            <input type="text" class="form-control @error('cnic') is-invalid @enderror" 
                                   id="cnic" name="cnic" value="{{ old('cnic', $teacher->cnic) }}" placeholder="12345-1234567-1">
                            @error('cnic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $teacher->phone) }}" placeholder="+92-300-1234567">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Salary & Employment Dates -->
                    <h6 class="mb-3">Salary & Employment Dates</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="hire_date" class="form-label">Hire Date</label>
                            <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                   id="hire_date" name="hire_date" value="{{ old('hire_date', $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : '') }}" disabled>
                            <small class="text-muted">Cannot be changed</small>
                        </div>
                        <div class="col-md-6">
                            <label for="salary_review_date" class="form-label">Salary Review Date</label>
                            <input type="date" class="form-control @error('salary_review_date') is-invalid @enderror" 
                                   id="salary_review_date" name="salary_review_date" value="{{ old('salary_review_date', $teacher->salary_review_date ? $teacher->salary_review_date->format('Y-m-d') : '') }}">
                            @error('salary_review_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <strong>Note:</strong> Base salary and hire date are managed separately. 
                            Contact the Finance department for salary adjustments.
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="{{ route('profile.teacher.show', $teacher->id) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
