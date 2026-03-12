@extends('layouts.vertical', ['title' => 'Leave Details', 'subTitle' => 'View Leave Request'])

@section('content')
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Leave Request Details</h5>
                <span class="badge @if($leave->isPending()) bg-warning @elseif($leave->isApproved()) bg-success @else bg-danger @endif" style="font-size: 12px;">
                    {{ ucfirst($leave->status) }}
                </span>
            </div>
            <div class="card-body">
                <!-- Student & Leave Type -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Student</h6>
                        <p class="fw-bold">{{ $leave->student->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Leave Type</h6>
                        <p class="fw-bold">
                            <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</span>
                        </p>
                    </div>
                </div>

                <!-- Dates & Duration -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Period</h6>
                        <p class="fw-bold">{{ $leave->from_date->format('M d, Y') }} to {{ $leave->to_date->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Number of Days</h6>
                        <p class="fw-bold"><span class="badge bg-secondary">{{ $leave->number_of_days }} days</span></p>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <h6 class="text-muted">Reason</h6>
                    <p>{{ $leave->reason }}</p>
                </div>

                <!-- Document -->
                @if($leave->document_path)
                    <div class="mb-4">
                        <h6 class="text-muted">Supporting Document</h6>
                        <p>
                            <a href="{{ asset('storage/' . $leave->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-download"></i> Download Document
                            </a>
                        </p>
                    </div>
                @endif

                <!-- Approval Info -->
                @if($leave->isApproved() || $leave->isRejected())
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Approved By</h6>
                            <p class="fw-bold">{{ $leave->approvedBy->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">{{ $leave->isApproved() ? 'Approved' : 'Rejected' }} At</h6>
                            <p class="fw-bold">{{ $leave->approved_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                        </div>
                    </div>
                @endif

                @if($leave->isRejected() && $leave->rejection_reason)
                    <div class="mb-4">
                        <h6 class="text-muted text-danger">Rejection Reason</h6>
                        <p class="text-danger fw-bold">{{ $leave->rejection_reason }}</p>
                    </div>
                @endif

                <!-- Request Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Requested On</h6>
                        <p class="fw-bold">{{ $leave->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Auto Attendance Mark</h6>
                        <p class="fw-bold">
                            @if($leave->auto_attendance)
                                <span class="badge bg-success">Enabled</span>
                            @else
                                <span class="badge bg-secondary">Disabled</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex gap-2">
                    @if($leave->isPending() && (auth()->user()->role === 'admin' || (auth()->user()->role === 'student' && auth()->user()->student?->id === $leave->student_id)))
                        <a href="{{ route('student-leaves.edit', $leave) }}" class="btn btn-warning">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <form action="{{ route('student-leaves.destroy', $leave) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this leave request?')">
                                <i class="bx bx-trash"></i> Delete
                            </button>
                        </form>
                    @endif

                    @if($leave->isPending() && auth()->user()->role !== 'student')
                        <a href="#approveModal" class="btn btn-success" data-bs-toggle="modal">
                            <i class="bx bx-check"></i> Approve
                        </a>
                        <a href="#rejectModal" class="btn btn-outline-danger" data-bs-toggle="modal">
                            <i class="bx bx-x"></i> Reject
                        </a>
                    @endif

                    <a href="{{ route('student-leaves.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('student-leaves.approve', $leave) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Add any remarks..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Leave</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Leave Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('student-leaves.reject', $leave) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason for Rejection *</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Please explain why you're rejecting this request..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Leave</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
