@extends('layouts.vertical', ['title' => 'Report Card', 'subTitle' => $student->user->name])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-0">📋 Academic Report Card</h4>
                    <small class="text-muted">{{ $student->user->name }}</small>
                </div>
                <a href="{{ route('students.report.pdf', $student) }}" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
            <div class="card-body">
                <!-- Student Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Student Information</h6>
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td class="fw-bold">Name:</td>
                                        <td>{{ $student->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Roll Number:</td>
                                        <td>{{ $student->roll_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Class:</td>
                                        <td>{{ $student->schoolClass->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Date of Birth:</td>
                                        <td>{{ $student->date_of_birth?->format('M d, Y') ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Attendance Summary</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="mb-1"><small class="text-muted">Present Days</small></p>
                                        <h5 class="text-success">{{ $attendance->where('status', 'present')->count() }}</h5>
                                    </div>
                                    <div class="col-6">
                                        <p class="mb-1"><small class="text-muted">Attendance %</small></p>
                                        <h5 class="text-primary">{{ round($attendancePercentage, 2) }}%</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grades by Term -->
                @if($grades->count() > 0)
                    @foreach($grades as $term => $termGrades)
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">{{ $term }} - Academic Performance</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Subject</th>
                                            <th>Exam Type</th>
                                            <th>Marks Obtained</th>
                                            <th>Total Marks</th>
                                            <th>Percentage</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($termGrades as $grade)
                                            <tr>
                                                <td class="fw-bold">{{ $grade->subject }}</td>
                                                <td><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $grade->exam_type)) }}</small></td>
                                                <td>{{ $grade->marks_obtained }}</td>
                                                <td>{{ $grade->total_marks }}</td>
                                                <td>
                                                    <span class="badge" style="background-color: {{ $grade->percentage >= 70 ? '#28a745' : ($grade->percentage >= 50 ? '#ffc107' : '#dc3545') }}; color: {{ $grade->percentage >= 50 && $grade->percentage < 70 ? '#000' : '#fff' }}">
                                                        {{ $grade->percentage }}%
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill badge-{{ $grade->grade == 'A+' || $grade->grade == 'A' ? 'success' : ($grade->grade == 'B' || $grade->grade == 'C' ? 'warning' : 'danger') }}">
                                                        {{ $grade->grade }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No grades recorded yet for this student.
                    </div>
                @endif

                <!-- Attendance Details -->
                <div class="mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Attendance Details</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="card-title">Present</h6>
                                    <h4 class="text-success">{{ $attendance->where('status', 'present')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="card-title">Absent</h6>
                                    <h4 class="text-danger">{{ $attendance->where('status', 'absent')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="card-title">Late</h6>
                                    <h4 class="text-warning">{{ $attendance->where('status', 'late')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h6 class="card-title">Excused</h6>
                                    <h4 class="text-info">{{ $attendance->where('status', 'excused')->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="alert alert-light border">
                    <small class="text-muted">
                        <i class="fas fa-calendar-alt"></i> Report generated on {{ now()->format('M d, Y H:i A') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection