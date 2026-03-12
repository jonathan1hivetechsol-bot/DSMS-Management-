@extends('layouts.vertical', ['title' => 'Attendance', 'subTitle' => 'Mark Attendance'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Today's Attendance - {{ today()->format('M d, Y') }}</h4>
                <div class="d-flex gap-2">
                    @can('create', App\Models\Attendance::class)
                        <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-primary">
                            <i class="ri-add-line"></i> Create New
                        </a>
                    @endcan
                    <a href="{{ route('attendance.report') }}" class="btn btn-sm btn-outline-primary">
                        <i class="ri-bar-chart-line"></i> Monthly Report
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="class-selector" class="form-label">Select Class</label>
                        <select class="form-select" id="class-selector" name="class_id" required>
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($classes->count() > 0)
                    <div class="mb-3">
                        <button type="button" id="bulk-present" class="btn btn-sm btn-success" disabled>
                            <i class="ri-check-line"></i> Mark All Present (except checked absent)
                        </button>
                    </div>
                @endif

                <div id="students-list" class="mt-4"></div>

                @if($classes->isEmpty())
                    <div class="alert alert-info">
                        <strong>No Classes Available</strong><br>
                        You don't have access to any classes. Please contact your administrator.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden data for students -->
<div id="all-students" style="display:none;">
    @foreach($classes as $class)
        @foreach($class->students as $student)
            <div class="student-data" data-class="{{ $class->id }}" data-student="{{ $student->id }}" data-name="{{ $student->user->name }}">
                <span class="status" data-status="{{ isset($attendances[$student->id]) ? $attendances[$student->id]['status'] : 'present' }}"></span>
                <span class="remarks" data-remarks="{{ isset($attendances[$student->id]) ? $attendances[$student->id]['remarks'] : '' }}"></span>
            </div>
        @endforeach
    @endforeach
</div>
@endsection

@section('script')
<script>
const todaysAttendance = @json($attendances);

function loadStudents(classId) {
    const studentsList = document.getElementById('students-list');
    const bulkButton = document.getElementById('bulk-present');
    
    if(!classId) {
        studentsList.innerHTML = '';
        bulkButton.disabled = true;
        return;
    }
    
    // Get all students for this class
    const studentElements = Array.from(document.querySelectorAll('.student-data'))
        .filter(el => el.getAttribute('data-class') === classId);
    
    if(studentElements.length === 0) {
        studentsList.innerHTML = '<div class="alert alert-warning">No students in this class.</div>';
        bulkButton.disabled = true;
        return;
    }
    
    let html = `<table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th style="width: 50px;"><input type="checkbox" id="check-all" title="Select all"></th>
                <th>Student Name</th>
                <th>Status</th>
                <th>Remarks</th>
                <th style="width: 100px;">Action</th>
            </tr>
        </thead>
        <tbody>`;
    
    studentElements.forEach(el => {
        const studentId = el.getAttribute('data-student');
        const studentName = el.getAttribute('data-name');
        const status = el.querySelector('.status')?.getAttribute('data-status') || 'present';
        const remarks = el.querySelector('.remarks')?.getAttribute('data-remarks') || '';
        const isAbsent = status === 'absent' ? 'checked' : '';
        const attendanceId = todaysAttendance[studentId]?.id;
        const actionUrl = attendanceId ? `/attendance/${attendanceId}/edit` : `/attendance/create?student_id=${studentId}`;
        const actionText = attendanceId ? 'Edit' : 'Create';
        
        html += `<tr>
            <td><input type="checkbox" class="absent-checkbox form-check-input" data-id="${studentId}" ${isAbsent}></td>
            <td>${studentName}</td>
            <td><span class="badge bg-${status === 'absent' ? 'danger' : 'success'}">${status}</span></td>
            <td>${remarks}</td>
            <td><a href="${actionUrl}" class="btn btn-xs btn-outline-primary">${actionText}</a></td>
        </tr>`;
    });
    
    html += '</tbody></table>';
    studentsList.innerHTML = html;
    bulkButton.disabled = false;
    
    // Add event listener to check-all checkbox
    const checkAllEl = document.getElementById('check-all');
    if(checkAllEl) {
        checkAllEl.addEventListener('change', function() {
            document.querySelectorAll('.absent-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
        });
    }
}

// Class selector change event
document.getElementById('class-selector').addEventListener('change', function() {
    loadStudents(this.value);
});

// Bulk mark present button
document.getElementById('bulk-present')?.addEventListener('click', function() {
    const classId = document.getElementById('class-selector').value;
    const absentIds = Array.from(document.querySelectorAll('.absent-checkbox:checked'))
        .map(el => el.getAttribute('data-id'));
    
    if(!classId) {
        alert('Please select a class first');
        return;
    }
    
    // Make bulk request
    fetch('{{ route("attendance.bulk") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            class_id: classId,
            attendance_date: '{{ today()->toDateString() }}',
            absent_ids: absentIds
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('An error occurred. Check the console for details.');
    });
});
</script>
@endsection
