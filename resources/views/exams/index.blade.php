@extends('layouts.vertical', ['title' => 'Exam Schedules', 'subTitle' => 'Manage Exams'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Exam Schedules</h4>
                <a href="{{ route('exam-schedules.create') }}" class="btn btn-primary">Schedule Exam</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Class</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $sched)
                                <tr>
                                    <td>{{ $sched->subject->name ?? 'N/A' }}</td>
                                    <td>{{ $sched->schoolClass->name ?? 'N/A' }}</td>
                                    <td>{{ $sched->exam_date->format('Y-m-d') }}</td>
                                    <td>{{ Str::limit($sched->description, 50) }}</td>
                                    <td>
                                        <a href="{{ route('exam-schedules.edit', $sched) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('exam-schedules.destroy', $sched) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this schedule?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No exams scheduled.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
</div>
@endsection