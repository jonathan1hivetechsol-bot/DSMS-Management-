@extends('layouts.vertical', ['title' => 'Create Leave Request', 'subTitle' => 'Submit New Leave Request'])

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Submit Leave Request</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('student-leaves.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Student *</label>
                        @if($isStudent ?? false)
                            <input type="text" class="form-control" value="{{ $students[0]->name ?? 'Unknown' }}" disabled>
                            <input type="hidden" name="student_id" value="{{ $students[0]->id ?? '' }}">
                        @else
                            <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                        {{ $student->name }} ({{ $student->roll_number ?? $student->id }})
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
                                <option value="">Select Type</option>
                                <option value="medical" @selected(old('leave_type') === 'medical')>Medical</option>
                                <option value="personal" @selected(old('leave_type') === 'personal')>Personal</option>
                                <option value="casual" @selected(old('leave_type') === 'casual')>Casual</option>
                                <option value="earned" @selected(old('leave_type') === 'earned')>Earned</option>
                                <option value="unpaid" @selected(old('leave_type') === 'unpaid')>Unpaid</option>
                            </select>
                            @error('leave_type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">From Date *</label>
                            <input type="date" name="from_date" class="form-control @error('from_date') is-invalid @enderror" 
                                   value="{{ old('from_date') }}" required>
                            @error('from_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">To Date *</label>
                            <input type="date" name="to_date" class="form-control @error('to_date') is-invalid @enderror" 
                                   value="{{ old('to_date') }}" required>
                            @error('to_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason for Leave *</label>
                        <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" 
                                  rows="4" placeholder="Please explain the reason for your leave request..." required>{{ old('reason') }}</textarea>
                        <small class="text-muted">Minimum 10 characters</small>
                        @error('reason')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Supporting Document (Optional)</label>
                        <input type="file" name="document" class="form-control @error('document') is-invalid @enderror" 
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">PDF, JPG, or PNG. Max 2MB. (For medical certificates, etc.)</small>
                        @error('document')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="auto_attendance" value="1" id="auto_attendance" checked>
                            <label class="form-check-label" for="auto_attendance">
                                Auto-mark attendance as "On Leave" during this period
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-send"></i> Submit Request
                        </button>
                        <a href="{{ route('student-leaves.index') }}" class="btn btn-secondary">
                            <i class="bx bx-x"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
