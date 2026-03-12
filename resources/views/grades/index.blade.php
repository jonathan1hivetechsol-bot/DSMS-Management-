@extends('layouts.vertical', ['title' => 'Grades', 'subTitle' => 'Manage Grades'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Grades</h4>
                <div>
                    <a href="{{ route('grades.index', ['export' => 1]) }}" class="btn btn-sm btn-outline-secondary me-2">Export CSV</a>
                    <a href="{{ route('grades.create') }}" class="btn btn-primary">Record Grade</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Percentage</th>
                                <th>Grade</th>
                                <th>Term</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grades as $grade)
                                <tr>
                                    <td>
                                        <strong>{{ $grade->student->user->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $grade->student->schoolClass->name ?? 'No Class' }}</small>
                                    </td>
                                    <td>{{ $grade->subject }}</td>
                                    <td>{{ $grade->marks_obtained }} / {{ $grade->total_marks }}</td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $grade->percentage >= 70 ? '#28a745' : ($grade->percentage >= 50 ? '#ffc107' : '#dc3545') }}">
                                            {{ $grade->percentage }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-{{ $grade->grade == 'A+' || $grade->grade == 'A' ? 'success' : ($grade->grade == 'B' || $grade->grade == 'C' ? 'warning' : 'danger') }}">
                                            {{ $grade->grade }}
                                        </span>
                                    </td>
                                    <td>{{ $grade->term }}</td>
                                    <td><small class="text-muted text-capitalize">{{ str_replace('_', ' ', $grade->exam_type) }}</small></td>
                                    <td>
                                        <a href="{{ route('grades.edit', $grade) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this grade?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center py-4">No grades recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $grades->links() }}
            </div>
        </div>
    </div>
</div>
@endsection