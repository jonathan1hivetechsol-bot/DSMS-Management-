@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Student Profile - {{ $student->user->name }}</h5>
                <a href="{{ route('profile.student.show', $student->id) }}" class="btn btn-sm btn-link">Back</a>
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

                <form action="{{ route('profile.student.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Student Identification -->
                    <h6 class="mb-3">Student Identification</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="student_id" value="{{ $student->student_id }}" disabled>
                            <small class="text-muted">Cannot be changed</small>
                        </div>
                        <div class="col-md-6">
                            <label for="registration_number" class="form-label">Registration Number</label>
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                   id="registration_number" name="registration_number" value="{{ old('registration_number', $student->registration_number) }}">
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="roll_number" class="form-label">Roll Number</label>
                            <input type="text" class="form-control @error('roll_number') is-invalid @enderror" 
                                   id="roll_number" name="roll_number" value="{{ old('roll_number', $student->roll_number) }}">
                            @error('roll_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="admission_status" class="form-label">Admission Status*</label>
                            <select class="form-select @error('admission_status') is-invalid @enderror" 
                                    id="admission_status" name="admission_status" required>
                                <option value="active" {{ old('admission_status', $student->admission_status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('admission_status', $student->admission_status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="graduated" {{ old('admission_status', $student->admission_status) === 'graduated' ? 'selected' : '' }}>Graduated</option>
                                <option value="transferred" {{ old('admission_status', $student->admission_status) === 'transferred' ? 'selected' : '' }}>Transferred</option>
                            </select>
                            @error('admission_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="admission_date" class="form-label">Admission Date</label>
                            <input type="date" class="form-control @error('admission_date') is-invalid @enderror" 
                                   id="admission_date" name="admission_date" value="{{ old('admission_date', $student->admission_date ? $student->admission_date->format('Y-m-d') : '') }}">
                            @error('admission_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="blood_group" class="form-label">Blood Group</label>
                            <select class="form-select @error('blood_group') is-invalid @enderror" 
                                    id="blood_group" name="blood_group">
                                <option value="">Select Blood Group</option>
                                <option value="O+" {{ old('blood_group', $student->blood_group) === 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_group', $student->blood_group) === 'O-' ? 'selected' : '' }}>O-</option>
                                <option value="A+" {{ old('blood_group', $student->blood_group) === 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_group', $student->blood_group) === 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_group', $student->blood_group) === 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_group', $student->blood_group) === 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_group', $student->blood_group) === 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_group', $student->blood_group) === 'AB-' ? 'selected' : '' }}>AB-</option>
                            </select>
                            @error('blood_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Contact Information -->
                    <h6 class="mb-3">Contact Information</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $student->phone) }}" placeholder="+92-300-1234567">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" name="address" value="{{ old('address', $student->address) }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Guardian Information -->
                    <h6 class="mb-3">Guardian Information</h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="parent_name" class="form-label">Guardian Name*</label>
                            <input type="text" class="form-control @error('parent_name') is-invalid @enderror" 
                                   id="parent_name" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}" required>
                            @error('parent_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="parent_phone" class="form-label">Guardian Phone*</label>
                            <input type="text" class="form-control @error('parent_phone') is-invalid @enderror" 
                                   id="parent_phone" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" required>
                            @error('parent_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="guardian_cnic" class="form-label">Guardian CNIC</label>
                            <input type="text" class="form-control @error('guardian_cnic') is-invalid @enderror" 
                                   id="guardian_cnic" name="guardian_cnic" value="{{ old('guardian_cnic', $student->guardian_cnic) }}" placeholder="12345-1234567-1">
                            @error('guardian_cnic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="guardian_occupation" class="form-label">Guardian Occupation</label>
                            <input type="text" class="form-control @error('guardian_occupation') is-invalid @enderror" 
                                   id="guardian_occupation" name="guardian_occupation" value="{{ old('guardian_occupation', $student->guardian_occupation) }}" placeholder="e.g., Doctor, Teacher">
                            @error('guardian_occupation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="guardian_phone_alt" class="form-label">Guardian Alternate Phone</label>
                        <input type="text" class="form-control @error('guardian_phone_alt') is-invalid @enderror" 
                               id="guardian_phone_alt" name="guardian_phone_alt" value="{{ old('guardian_phone_alt', $student->guardian_phone_alt) }}" placeholder="+92-300-1234567">
                        @error('guardian_phone_alt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <!-- Medical Information -->
                    <h6 class="mb-3">Medical Information</h6>

                    <div class="mb-3">
                        <label for="medical_conditions" class="form-label">Medical Conditions</label>
                        <textarea class="form-control @error('medical_conditions') is-invalid @enderror" 
                                  id="medical_conditions" name="medical_conditions" rows="3" placeholder="Any allergies, conditions, or special medical requirements">{{ old('medical_conditions', $student->medical_conditions) }}</textarea>
                        @error('medical_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <!-- Academic Information -->
                    <h6 class="mb-3">Academic Information</h6>

                    <div class="mb-3">
                        <label for="previous_school" class="form-label">Previous School</label>
                        <input type="text" class="form-control @error('previous_school') is-invalid @enderror" 
                               id="previous_school" name="previous_school" value="{{ old('previous_school', $student->previous_school) }}" placeholder="School name">
                        @error('previous_school')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks / Additional Notes</label>
                        <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                  id="remarks" name="remarks" rows="3" placeholder="Any additional information about the student">{{ old('remarks', $student->remarks) }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="{{ route('profile.student.show', $student->id) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
