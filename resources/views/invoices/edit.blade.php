@extends('layouts.vertical', ['title' => 'Edit Invoice', 'subTitle' => 'Modify'])

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Invoice</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Student</label>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $invoice->student_id == $student->id ? 'selected' : '' }}>{{ $student->user->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="class_id" class="form-label">Class (optional)</label>
                        <select name="class_id" id="class_id" class="form-select">
                            <option value="">-- None --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $invoice->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ $invoice->amount }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ optional($invoice->due_date)->toDateString() }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ $invoice->description }}</textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection