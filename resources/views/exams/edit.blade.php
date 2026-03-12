@extends('layouts.vertical', ['title' => 'Edit Exam Schedule', 'subTitle' => 'Modify'])

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Exam Schedule</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('exam-schedules.update', $examSchedule) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $examSchedule->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $examSchedule->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="exam_date" class="form-label">Exam Date</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control" value="{{ $examSchedule->exam_date->toDateString() }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ $examSchedule->description }}</textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('exam-schedules.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection