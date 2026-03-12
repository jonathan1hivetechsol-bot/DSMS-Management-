@extends('layouts.vertical', ['title' => 'Edit Leave Request', 'subTitle' => 'Update Leave Request'])

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Edit Leave Request</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('student-leaves.update', $leave) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Student *</label>
                        @if($isStudent ?? false)
                            <input type="text" class="form-control" value="{{ $leave->student->name ?? 'Unknown' }}" disabled>
                            <input type="hidden" name="student_id" value="{{ $leave->student_id }}">
                        @else
                            <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" @selected(old('student_id', $leave->student_id) == $student->id)>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        @error('student_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Leave Type *</label>
                            <select name="leave_type" class="form-select @error('leave_type') is-invalid @enderror" required>
                                @foreach($leaveTypes as $key => $label)
                                    <option value="{{ $key }}" @selected(old('leave_type', $leave->leave_type) === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('leave_type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">From Date *</label>
                            <input type="date" name="from_date" class="form-control @error('from_date') is-invalid @enderror" 
                                   value="{{ old('from_date', $leave->from_date->format('Y-m-d')) }}" required>
                            @error('from_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">To Date *</label>
                            <input type="date" name="to_date" class="form-control @error('to_date') is-invalid @enderror" 
                                   value="{{ old('to_date', $leave->to_date->format('Y-m-d')) }}" required>
                            @error('to_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason for Leave *</label>
                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" 
                                  rows="4" required>{{ old('reason', $leave->reason) }}</textarea>
                        @error('reason')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Supporting Document (Optional)</label>
                        @if($leave->document_path)
                            <p class="mb-2"><small class="text-muted">Current: <a href="{{ asset('storage/' . $leave->document_path) }}" target="_blank">View Document</a></small></p>
                        @endif
                        <input type="file" name="document" class="form-control @error('document') is-invalid @enderror" 
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">PDF, JPG, or PNG. Max 2MB.</small>
                        @error('document')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="auto_attendance" value="1" id="auto_attendance"
                                   @checked(old('auto_attendance', $leave->auto_attendance))>
                            <label class="form-check-label" for="auto_attendance">
                                Auto-mark attendance as "On Leave"
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Update Request
                        </button>
                        <a href="{{ route('student-leaves.show', $leave) }}" class="btn btn-secondary">
                            <i class="bx bx-x"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
