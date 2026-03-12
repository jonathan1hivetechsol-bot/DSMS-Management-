@extends('layouts.vertical', ['title' => 'Edit Attendance', 'subTitle' => 'Update Teacher Attendance'])

@section('content')
<div class="container-xxl">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h2 class="text-primary fw-bold">Edit Attendance</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Update Teacher Attendance Record</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher-attendance.update', $teacherAttendance) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                    <option value="">-- Select Teacher --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $teacherAttendance->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->user->name }} ({{ $teacher->subject }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="attendance_date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" name="attendance_date" id="attendance_date" value="{{ old('attendance_date', $teacherAttendance->attendance_date->toDateString()) }}" class="form-control @error('attendance_date') is-invalid @enderror" required>
                                @error('attendance_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required onchange="toggleLeaveType()">
                                    <option value="">-- Select Status --</option>
                                    <option value="present" {{ old('status', $teacherAttendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ old('status', $teacherAttendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ old('status', $teacherAttendance->status) == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="leave" {{ old('status', $teacherAttendance->status) == 'leave' ? 'selected' : '' }}>Leave</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="leave-type-div" style="display: none;">
                                <label for="leave_type" class="form-label">Leave Type</label>
                                <select name="leave_type" id="leave_type" class="form-select @error('leave_type') is-invalid @enderror">
                                    <option value="">-- Select Leave Type --</option>
                                    <option value="medical" {{ old('leave_type', $teacherAttendance->leave_type) == 'medical' ? 'selected' : '' }}>Medical</option>
                                    <option value="casual" {{ old('leave_type', $teacherAttendance->leave_type) == 'casual' ? 'selected' : '' }}>Casual</option>
                                    <option value="earned" {{ old('leave_type', $teacherAttendance->leave_type) == 'earned' ? 'selected' : '' }}>Earned</option>
                                    <option value="unpaid" {{ old('leave_type', $teacherAttendance->leave_type) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                </select>
                                @error('leave_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3" placeholder="Enter any remarks...">{{ old('remarks', $teacherAttendance->remarks) }}</textarea>
                                @error('remarks')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Update Attendance
                                </button>
                                <a href="{{ route('teacher-attendance.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back"></i> Back
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleLeaveType() {
    const status = document.getElementById('status').value;
    const leaveTypeDiv = document.getElementById('leave-type-div');
    if (status === 'leave') {
        leaveTypeDiv.style.display = 'block';
    } else {
        leaveTypeDiv.style.display = 'none';
    }
}

// Call on page load
document.addEventListener('DOMContentLoaded', toggleLeaveType);
</script>
@endsection
