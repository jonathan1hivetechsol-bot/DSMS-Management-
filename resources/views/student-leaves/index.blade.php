@extends('layouts.vertical', ['title' => 'Student Leaves', 'subTitle' => 'Manage Leave Requests'])

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="text-primary">Student Leave Requests</h4>
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'student')
            <a href="{{ route('student-leaves.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> New Leave Request
            </a>
        @endif
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Filter Requests</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('student-leaves.index') }}" class="row g-3">
                    @if(auth()->user()->role !== 'student')
                        <div class="col-md-3">
                            <label class="form-label">Student</label>
                            <select name="student_id" class="form-select">
                                <option value="">All Students</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-@if(auth()->user()->role !== 'student')3@else 4@endif">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                            <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                            <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-@if(auth()->user()->role !== 'student')2@else 4@endif">
                        <label class="form-label">Type</label>
                        <select name="leave_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="medical" @selected(request('leave_type') === 'medical')>Medical</option>
                            <option value="personal" @selected(request('leave_type') === 'personal')>Personal</option>
                            <option value="casual" @selected(request('leave_type') === 'casual')>Casual</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Leaves Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                @if(auth()->user()->role !== 'student')
                                    <th>Student</th>
                                @endif
                                <th>Type</th>
                                <th>Period</th>
                                <th>Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                                <tr>
                                    @if(auth()->user()->role !== 'student')
                                        <td><strong>{{ $leave->student->name }}</strong></td>
                                    @endif
                                    <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</span></td>
                                    <td>{{ $leave->from_date->format('M d') }} - {{ $leave->to_date->format('M d, Y') }}</td>
                                    <td><span class="badge bg-secondary">{{ $leave->number_of_days }} days</span></td>
                                    <td>{{ Str::limit($leave->reason, 30) }}</td>
                                    <td>
                                        <span class="badge @if($leave->isPending()) bg-warning @elseif($leave->isApproved()) bg-success @else bg-danger @endif">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td><small class="text-muted">{{ $leave->created_at->format('M d') }}</small></td>
                                    <td>
                                        <a href="{{ route('student-leaves.show', $leave) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-eye"></i>
                                        </a>
                                        @if($leave->isPending() && (auth()->user()->role === 'admin' || auth()->id() === $leave->student?->user_id))
                                            <a href="{{ route('student-leaves.edit', $leave) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No leave requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $leaves->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
