@extends('layouts.vertical', ['title' => 'Payroll Details', 'subTitle' => 'View Payroll Information'])

@section('content')
<div class="container-xxl">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h2 class="text-primary fw-bold">Payroll Details</h2>
        </div>
        <div class="col-md-7 text-end">
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary btn-sm">
                <i class="bx bx-arrow-back"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Payroll Info -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Payroll for {{ $payroll->teacher->user->name }} - {{ \Carbon\Carbon::createFromDate($payroll->year, $payroll->month, 1)->format('F Y') }}</h5>
                        @php
                            $statusColor = [
                                'pending' => 'warning',
                                'approved' => 'info',
                                'paid' => 'success'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColor[$payroll->status] }}">{{ ucfirst($payroll->status) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">Teacher Name</label>
                                <p class="fw-bold">{{ $payroll->teacher->user->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted">Subject</label>
                                <p class="fw-bold">{{ $payroll->teacher->subject }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">Payroll Month</label>
                                <p class="fw-bold">{{ \Carbon\Carbon::createFromDate($payroll->year, $payroll->month, 1)->format('F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Attendance Details</h6>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Working Days:</span>
                                    <strong>{{ $payroll->working_days }}</strong>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Days Present:</span>
                                    <strong class="text-success">{{ $payroll->present_days }}</strong>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Days Absent:</span>
                                    <strong class="text-danger">{{ $payroll->absent_days }}</strong>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Days Leave:</span>
                                    <strong class="text-info">{{ $payroll->leave_days }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Salary Breakdown</h6>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Base Salary:</span>
                                    <strong>Rs.{{ number_format($payroll->base_salary, 2) }}</strong>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Allowances:</span>
                                    <strong class="text-success">+Rs.{{ number_format($payroll->allowances, 2) }}</strong>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Deductions:</span>
                                    <strong class="text-danger">-Rs.{{ number_format($payroll->deductions, 2) }}</strong>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Gross Salary:</span>
                                    <strong>Rs.{{ number_format($payroll->gross_salary, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Net Salary (Amount to be Paid)</h5>
                                    <h4 class="text-success fw-bold mb-0">Rs.{{ number_format($payroll->net_salary, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($payroll->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="fw-bold">Notes</h6>
                                <p class="text-muted">{{ $payroll->notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($payroll->payment_date)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-success" role="alert">
                                    <strong>Payment Date:</strong> {{ $payroll->payment_date->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    @if($payroll->status === 'pending')
                        <div class="mb-2">
                            <a href="{{ route('payroll.edit', $payroll) }}" class="btn btn-primary btn-sm w-100">
                                <i class="bx bx-edit-alt"></i> Edit Payroll
                            </a>
                        </div>
                        <div class="mb-2">
                            <a href="{{ route('payroll.approve', $payroll) }}" class="btn btn-info btn-sm w-100">
                                <i class="bx bx-check-double"></i> Approve
                            </a>
                        </div>
                        <div class="mb-2">
                            <form action="{{ route('payroll.destroy', $payroll) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure?')">
                                    <i class="bx bx-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    @elseif($payroll->status === 'approved')
                        <div class="mb-2">
                            <a href="{{ route('payroll.markPaid', $payroll) }}" class="btn btn-success btn-sm w-100">
                                <i class="bx bx-check"></i> Mark as Paid
                            </a>
                        </div>
                    @elseif($payroll->status === 'paid')
                        <div class="alert alert-success">
                            <p class="mb-0"><strong>Status:</strong> This payroll has been paid.</p>
                            <p class="mb-0"><strong>Date:</strong> {{ $payroll->payment_date->format('d M Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Gross Salary</label>
                        <p class="fw-bold fs-5">${{ number_format($payroll->gross_salary, 2) }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Total Deductions</label>
                        <p class="fw-bold fs-5 text-danger">${{ number_format($payroll->deductions, 2) }}</p>
                    </div>
                    <hr>
                    <div>
                        <label class="text-muted small">Net Payable</label>
                        <p class="fw-bold fs-5 text-success">${{ number_format($payroll->net_salary, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
