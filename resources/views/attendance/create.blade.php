@extends('layouts.vertical', ['title' => 'Attendance', 'subTitle' => 'Mark Attendance'])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Mark Attendance</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('attendance.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror" required onchange="loadStudents()">
                                    <option value="">Select a class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                    <option value="">Select a student</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" data-class="{{ $student->class_id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->user->name }}</option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="attendance_date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" name="attendance_date" id="attendance_date" class="form-control @error('attendance_date') is-invalid @enderror" value="{{ old('attendance_date', today()->format('Y-m-d')) }}" required>
                                @error('attendance_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="">Select status</option>
                                    <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="excused" {{ old('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror" placeholder="Any additional notes...">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Mark Attendance</button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadStudents() {
    const classId = document.getElementById('class_id').value;
    const studentSelect = document.getElementById('student_id');
    const options = studentSelect.querySelectorAll('option:not(:first-child)');
    
    options.forEach(option => {
        if (classId && option.getAttribute('data-class') === classId) {
            option.style.display = 'block';
        } else if (classId) {
            option.style.display = 'none';
        } else {
            option.style.display = 'block';
        }
    });
    
    studentSelect.value = '';
}
</script>
@endsection
