@extends('layouts.vertical', ['title' => 'Payroll', 'subTitle' => 'Manage Teacher Payroll'])

@section('content')
<div class="container-xxl">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h2 class="text-primary fw-bold">Payroll Management</h2>
        </div>
        <div class="col-md-7 text-end d-flex justify-content-end align-items-center gap-2">
            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#generateModal">
                <i class="bx bx-file-blank"></i> Generate Payroll
            </button>
            <a href="{{ route('payroll.create') }}" class="btn btn-primary btn-sm">
                <i class="bx bx-plus"></i> Add Payroll
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('payroll.index') }}" class="row g-3">
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
                            <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Reset</a>
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
                            <span class="text-muted">Total Amount</span>
                            <h3 class="text-primary fw-bold">Rs.{{ number_format($stats['total_amount'], 2) }}</h3>
                        </div>
                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                            <i class="bx bx-money text-primary fs-1"></i>
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
                            <span class="text-muted">Pending</span>
                            <h3 class="text-warning fw-bold">{{ $stats['pending'] }}</h3>
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
                            <span class="text-muted">Approved</span>
                            <h3 class="text-info fw-bold">{{ $stats['approved'] }}</h3>
                        </div>
                        <div class="avatar-md bg-info bg-opacity-10 rounded">
                            <i class="bx bx-check-double text-info fs-1"></i>
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
                            <span class="text-muted">Paid</span>
                            <h3 class="text-success fw-bold">{{ $stats['paid'] }}</h3>
                        </div>
                        <div class="avatar-md bg-success bg-opacity-10 rounded">
                            <i class="bx bx-check text-success fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payroll Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    @if($payrolls->count() > 0)
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Teacher</th>
                                    <th>Base Salary</th>
                                    <th>Gross Salary</th>
                                    <th>Deductions</th>
                                    <th>Net Salary</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payrolls as $payroll)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                    <span class="text-primary fw-bold">{{ substr($payroll->teacher->user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-bold">{{ $payroll->teacher->user->name }}</p>
                                                    <small class="text-muted">{{ $payroll->teacher->subject }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold">${{ number_format($payroll->base_salary, 2) }}</td>
                                        <td>${{ number_format($payroll->gross_salary, 2) }}</td>
                                        <td class="text-danger">${{ number_format($payroll->deductions, 2) }}</td>
                                        <td class="fw-bold text-success">${{ number_format($payroll->net_salary, 2) }}</td>
                                        <td>
                                            @php
                                                $statusColor = [
                                                    'pending' => 'warning',
                                                    'approved' => 'info',
                                                    'paid' => 'success'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColor[$payroll->status] }}">
                                                {{ ucfirst($payroll->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('payroll.show', $payroll) }}">
                                                            <i class="bx bx-eye"></i> View
                                                        </a>
                                                    </li>
                                                    @if($payroll->status === 'pending')
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('payroll.edit', $payroll) }}">
                                                                <i class="bx bx-edit-alt"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('payroll.approve', $payroll) }}">
                                                                <i class="bx bx-check-double"></i> Approve
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($payroll->status === 'approved')
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('payroll.markPaid', $payroll) }}">
                                                                <i class="bx bx-check"></i> Mark as Paid
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if($payroll->status !== 'paid')
                                                        <li>
                                                            <form action="{{ route('payroll.destroy', $payroll) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                                    <i class="bx bx-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
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
                            <p class="text-muted">No payroll records found for the selected month.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Payroll Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Payroll</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('payroll.generate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="gen_year" class="form-label">Year <span class="text-danger">*</span></label>
                            <select name="year" id="gen_year" class="form-select" required>
                                @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="gen_month" class="form-label">Month <span class="text-danger">*</span></label>
                            <select name="month" id="gen_month" class="form-select" required>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(now()->year, $m, 1)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <p class="text-muted small">This will generate payroll for all teachers based on their attendance for the selected month.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
