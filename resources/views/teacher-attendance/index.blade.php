@extends('layouts.vertical', ['title' => 'Teacher Attendance', 'subTitle' => 'Manage Teacher Attendance'])

@section('content')
<div class="container-xxl">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h2 class="text-primary fw-bold">Teacher Attendance</h2>
        </div>
        <div class="col-md-7 text-end d-flex justify-content-end align-items-center gap-2">
            <a href="{{ route('teacher-attendance.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus"></i> Mark Attendance
            </a>
            <a href="{{ route('teacher-attendance.report') }}" class="btn btn-info btn-sm">
                <i class="bx bx-file"></i> Report
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('teacher-attendance.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="year" class="form-label">Year</label>
                            <select name="year" id="year" class="form-select">
                                @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="month" class="form-label">Month</label>
                            <select name="month" id="month" class="form-select">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(now()->year, $m, 1)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 d-flex gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('teacher-attendance.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted">Present</span>
                            <h3 class="text-success fw-bold">{{ $stats['present'] }}</h3>
                        </div>
                        <div class="avatar-md bg-success bg-opacity-10 rounded">
                            <i class="bx bx-check text-success fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted">Absent</span>
                            <h3 class="text-danger fw-bold">{{ $stats['absent'] }}</h3>
                        </div>
                        <div class="avatar-md bg-danger bg-opacity-10 rounded">
                            <i class="bx bx-x text-danger fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted">Late</span>
                            <h3 class="text-warning fw-bold">{{ $stats['late'] }}</h3>
                        </div>
                        <div class="avatar-md bg-warning bg-opacity-10 rounded">
                            <i class="bx bx-time text-warning fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted">Leave</span>
                            <h3 class="text-info fw-bold">{{ $stats['leave'] }}</h3>
                        </div>
                        <div class="avatar-md bg-info bg-opacity-10 rounded">
                            <i class="bx bx-calendar text-info fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    @if($attendances->count() > 0)
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Teacher</th>
                                    <th>Status</th>
                                    <th>Leave Type</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td class="fw-bold">{{ $attendance->attendance_date->format('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                    <span class="text-primary fw-bold">{{ substr($attendance->teacher->user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-bold">{{ $attendance->teacher->user->name }}</p>
                                                    <small class="text-muted">{{ $attendance->teacher->subject }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusColor = [
                                                    'present' => 'success',
                                                    'absent' => 'danger',
                                                    'late' => 'warning',
                                                    'leave' => 'info'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColor[$attendance->status] }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($attendance->leave_type)
                                                <span class="badge bg-light text-dark">{{ ucfirst($attendance->leave_type) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->remarks)
                                                <small>{{ $attendance->remarks }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('teacher-attendance.edit', $attendance) }}">
                                                            <i class="bx bx-edit-alt"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('teacher-attendance.destroy', $attendance) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                                <i class="bx bx-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-5 text-center">
                            <div class="avatar-lg mx-auto mb-3 bg-light rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bx bx-folder-open fs-2 text-muted"></i>
                            </div>
                            <p class="text-muted">No attendance records found for the selected month.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
