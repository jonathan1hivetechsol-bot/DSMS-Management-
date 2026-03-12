@extends('layouts.vertical', ['title' => 'Invoices', 'subTitle' => 'Manage Billing'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Invoices</h4>
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary">New Invoice</a>
                </div>
                <form method="GET" class="row g-2 mt-2">
                    <div class="col-md-3">
                        <select name="student_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Student --</option>
                            @foreach(\App\Models\Student::all() as $stu)
                                <option value="{{ $stu->id }}" {{ request('student_id') == $stu->id ? 'selected' : '' }}>{{ $stu->user->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="class_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Class --</option>
                            @foreach(\App\Models\SchoolClass::all() as $cl)
                                <option value="{{ $cl->id }}" {{ request('class_id') == $cl->id ? 'selected' : '' }}>{{ $cl->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Amount</th>
                                <th>Due</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $inv)
                                <tr>
                                    <td>{{ $inv->id }}</td>
                                    <td>{{ $inv->student->user->name ?? 'N/A' }}</td>
                                    <td>{{ $inv->schoolClass?->name }}</td>
                                    <td>Rs.{{ number_format($inv->amount,2) }}</td>
                                    <td>{{ $inv->due_date?->format('Y-m-d') }}</td>
                                    <td>{{ $inv->isPaid() ? 'Paid' : 'Unpaid' }}</td>
                                    <td>
                                        <a href="{{ route('invoices.edit', $inv) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @if(!$inv->isPaid())
                                            <a href="{{ route('invoices.markPaid', $inv) }}" class="btn btn-sm btn-success">Mark Paid</a>
                                        @endif
                                        <a href="{{ route('invoices.pdf', $inv) }}" class="btn btn-sm btn-secondary">PDF</a>
                                        <form action="{{ route('invoices.destroy', $inv) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">No invoices.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection