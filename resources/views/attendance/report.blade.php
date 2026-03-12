@extends('layouts.vertical', ['title' => 'Attendance Report', 'subTitle' => 'Monthly'])

@section('content')
<div class="row">
    <div class="col-lg-3">
            <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('attendance.report') }}">
                    @if(auth()->user()->role !== 'student')
                        <div class="mb-3">
                            <label for="class_id" class="form-label">Class</label>
                            <select name="class_id" id="class_id" class="form-select">
                                <option value="">-- Choose --</option>
                                @foreach(
                                    \App\Models\SchoolClass::all() as $cl)
                                    <option value="{{ $cl->id }}" {{ $classId == $cl->id ? 'selected' : '' }}>{{ $cl->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="month" class="form-label">Month</label>
                        <input type="month" name="month" id="month" class="form-control" value="{{ $month }}">
                    </div>
                    <button class="btn btn-primary">View</button>
                    @if($classId)
                        <a href="{{ route('attendance.report', ['class_id'=>$classId,'month'=>$month,'export'=>1]) }}" class="btn btn-outline-secondary ms-2">Export CSV</a>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        @if($classId)
            <div class="card">
                <div class="card-body">
                    <h5>Attendance for {{ \App\Models\SchoolClass::find($classId)->name }} ({{ $month }})</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>Student</th><th>Days Present</th><th>Days Absent</th><th>Late</th><th>Excused</th></tr>
                        </thead>
                        <tbody>
                            @foreach($students as $stu)
                                @php
                                    $records = $attendances->get($stu->id, collect());
                                    $present = $records->where('status','present')->count();
                                    $absent = $records->where('status','absent')->count();
                                    $late = $records->where('status','late')->count();
                                    $excused = $records->where('status','excused')->count();
                                @endphp
                            <tr>
                                <td>{{ $stu->user->name }}</td>
                                <td>{{ $present }}</td>
                                <td>{{ $absent }}</td>
                                <td>{{ $late }}</td>
                                <td>{{ $excused }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection