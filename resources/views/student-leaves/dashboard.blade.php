@extends('layouts.vertical', ['title' => 'Leave Dashboard', 'subTitle' => 'Statistics & Overview'])

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-muted">Pending</h5>
                <h2 class="text-warning fw-bold">{{ $stats['total_pending'] }}</h2>
                <small class="text-muted">Awaiting approval</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-muted">Approved</h5>
                <h2 class="text-success fw-bold">{{ $stats['total_approved'] }}</h2>
                <small class="text-muted">Approved requests</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-muted">Rejected</h5>
                <h2 class="text-danger fw-bold">{{ $stats['total_rejected'] }}</h2>
                <small class="text-muted">Rejected requests</small>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-muted">On Leave Now</h5>
                <h2 class="text-info fw-bold">{{ $stats['currently_on_leave'] }}</h2>
                <small class="text-muted">Students on leave</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Leaves -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Recent Leave Requests</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Student</th>
                                <th>Type</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th>Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLeaves as $leave)
                                <tr>
                                    <td>{{ $leave->student->name }}</td>
                                    <td><small class="text-muted">{{ ucfirst($leave->leave_type) }}</small></td>
                                    <td>{{ $leave->from_date->format('M d') }} - {{ $leave->to_date->format('M d') }}</td>
                                    <td>
                                        <span class="badge @if($leave->isPending()) bg-warning @elseif($leave->isApproved()) bg-success @else bg-danger @endif">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $leave->number_of_days }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No recent leaves</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaves by Type -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Leaves by Type</h5>
            </div>
            <div class="card-body">
                @if($leavesByType->isEmpty())
                    <p class="text-muted text-center py-3">No approved leaves yet</p>
                @else
                    <div>
                        @foreach($leavesByType as $type)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="fw-bold">{{ ucfirst($type->leave_type) }}</small>
                                    <small class="text-muted">{{ $type->count }}</small>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" style="width: {{ ($type->count / $leavesByType->sum('count')) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
