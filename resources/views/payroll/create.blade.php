@extends('layouts.vertical', ['title' => 'Create Payroll', 'subTitle' => 'Add New Payroll Record'])

@section('content')
<div class="container-xxl">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h2 class="text-primary fw-bold">Add Payroll</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Create New Payroll Record</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payroll.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                    <option value="">-- Select Teacher --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->user->name }} - Rs.{{ $teacher->salary }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="base_salary" class="form-label">Base Salary <span class="text-danger">*</span></label>
                                <input type="number" name="base_salary" id="base_salary" step="0.01" value="{{ old('base_salary') }}" class="form-control @error('base_salary') is-invalid @enderror" required>
                                @error('base_salary')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                                <select name="year" id="year" class="form-select @error('year') is-invalid @enderror" required>
                                    @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                        <option value="{{ $y }}" {{ old('year', $year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                @error('year')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="month" class="form-label">Month <span class="text-danger">*</span></label>
                                <select name="month" id="month" class="form-select @error('month') is-invalid @enderror" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ old('month', $month) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::createFromDate(now()->year, $m, 1)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                                @error('month')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="working_days" class="form-label">Working Days <span class="text-danger">*</span></label>
                                <input type="number" name="working_days" id="working_days" value="{{ old('working_days', 26) }}" class="form-control @error('working_days') is-invalid @enderror" required>
                                @error('working_days')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="present_days" class="form-label">Present Days <span class="text-danger">*</span></label>
                                <input type="number" name="present_days" id="present_days" value="{{ old('present_days', 26) }}" class="form-control @error('present_days') is-invalid @enderror" required>
                                @error('present_days')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="absent_days" class="form-label">Absent Days <span class="text-danger">*</span></label>
                                <input type="number" name="absent_days" id="absent_days" value="{{ old('absent_days', 0) }}" class="form-control @error('absent_days') is-invalid @enderror" required>
                                @error('absent_days')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="leave_days" class="form-label">Leave Days <span class="text-danger">*</span></label>
                                <input type="number" name="leave_days" id="leave_days" value="{{ old('leave_days', 0) }}" class="form-control @error('leave_days') is-invalid @enderror" required>
                                @error('leave_days')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="allowances" class="form-label">Allowances</label>
                                <input type="number" name="allowances" id="allowances" step="0.01" value="{{ old('allowances', 0) }}" class="form-control @error('allowances') is-invalid @enderror">
                                @error('allowances')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="deductions" class="form-label">Deductions</label>
                                <input type="number" name="deductions" id="deductions" step="0.01" value="{{ old('deductions', 0) }}" class="form-control @error('deductions') is-invalid @enderror">
                                @error('deductions')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Enter any notes...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Create Payroll
                                </button>
                                <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
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
@endsection
