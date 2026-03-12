@extends('layouts.vertical', ['title' => 'Add Grade', 'subTitle' => 'Create'])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Record Student Grade</h4>
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

                <form action="{{ route('grades.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                            <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id')==$student->id?'selected':'' }}>
                                        {{ $student->user->name ?? 'N/A' }} ({{ $student->schoolClass->name ?? 'No Class' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="e.g., Mathematics" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="marks_obtained" class="form-label">Marks Obtained <span class="text-danger">*</span></label>
                            <input type="number" name="marks_obtained" id="marks_obtained" class="form-control @error('marks_obtained') is-invalid @enderror" value="{{ old('marks_obtained') }}" step="0.01" placeholder="0.00" required>
                            @error('marks_obtained')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="total_marks" class="form-label">Total Marks <span class="text-danger">*</span></label>
                            <input type="number" name="total_marks" id="total_marks" class="form-control @error('total_marks') is-invalid @enderror" value="{{ old('total_marks', 100) }}" step="0.01" placeholder="100" required>
                            @error('total_marks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="term" class="form-label">Term <span class="text-danger">*</span></label>
                            <input type="text" name="term" id="term" class="form-control @error('term') is-invalid @enderror" value="{{ old('term', 'Spring') }}" placeholder="e.g., Spring" required>
                            @error('term')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="exam_type" class="form-label">Exam Type <span class="text-danger">*</span></label>
                            <select name="exam_type" id="exam_type" class="form-select @error('exam_type') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="midterm" {{ old('exam_type')=='midterm'?'selected':'' }}>Midterm</option>
                                <option value="final" {{ old('exam_type')=='final'?'selected':'' }}>Final</option>
                                <option value="quiz" {{ old('exam_type')=='quiz'?'selected':'' }}>Quiz</option>
                                <option value="assignment" {{ old('exam_type')=='assignment'?'selected':'' }}>Assignment</option>
                            </select>
                            @error('exam_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3" placeholder="Optional remarks">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Grade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection