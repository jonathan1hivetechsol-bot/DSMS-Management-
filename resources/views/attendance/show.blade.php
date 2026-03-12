@extends('layouts.vertical', ['title' => 'Attendance', 'subTitle' => 'Attendance Details'])

@section('content')
<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">Attendance Details</h4>
                    <p class="text-muted mb-0">{{ $attendance->student->user->name }} - {{ $attendance->attendance_date->format('M d, Y') }}</p>
                </div>
                <div class="d-flex gap-2">
                    @can('update', $attendance)
                        <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-primary btn-sm">
                            <i class="ri-edit-2-line"></i> Edit
                        </a>
                    @endcan
                    @can('delete', $attendance)
                        <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this attendance record?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="ri-delete-bin-line"></i> Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Student</label>
                        <p class="mb-1"><strong>{{ $attendance->student->user->name }}</strong></p>
                        <p class="text-muted small mb-0">{{ $attendance->student->user->email }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Class</label>
                        <p><strong>{{ $attendance->schoolClass->name }}</strong></p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Date</label>
                        <p class="mb-1"><strong>{{ $attendance->attendance_date->format('M d, Y') }}</strong></p>
                        <p class="text-muted small mb-0">{{ $attendance->attendance_date->format('l') }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <p>
                            @php
                                $statusBadges = [
                                    'present' => 'success',
                                    'absent' => 'danger',
                                    'late' => 'warning',
                                    'excused' => 'info',
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusBadges[$attendance->status] ?? 'secondary' }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Remarks</label>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $attendance->remarks ?? 'No remarks' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Created</label>
                        <p class="text-muted small mb-0">{{ $attendance->created_at->format('M d, Y H:i A') }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Last Updated</label>
                        <p class="text-muted small mb-0">{{ $attendance->updated_at->format('M d, Y H:i A') }}</p>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex gap-2">
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to List
                </a>
                <a href="{{ route('attendance.report', ['class_id' => $attendance->schoolClass->id]) }}" class="btn btn-info">
                    <i class="ri-bar-chart-line"></i> View Report
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
