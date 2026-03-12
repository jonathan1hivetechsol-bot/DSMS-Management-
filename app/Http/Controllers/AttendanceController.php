<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index()
    {
        $this->authorize('viewAny', Attendance::class);

        if (auth()->user()->role === 'teacher') {
            $teacher = auth()->user()->teacher;
            $classes = $teacher ? $teacher->schoolClasses()->with('students')->get() : collect();
        } elseif (auth()->user()->role === 'student') {
            $student = auth()->user()->student;
            $classes = $student ? SchoolClass::with('students')->where('id', $student->class_id)->get() : collect();
        } else {
            $classes = SchoolClass::with('students')->get();
        }

        $attendances = Attendance::with('student.user', 'schoolClass')
            ->whereDate('attendance_date', today())
            ->get()
            ->keyBy('student_id');

        return view('attendance.index', compact('classes', 'attendances'));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create()
    {
        $this->authorize('create', Attendance::class);

        $classes = $this->getAccessibleClasses();
        $students = Student::whereIn('class_id', $classes->pluck('id'))->with('user')->get();

        return view('attendance.create', compact('classes', 'students'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Attendance::class);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:school_classes,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:255',
        ]);

        Attendance::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'attendance_date' => $validated['attendance_date'],
            ],
            [
                'class_id' => $validated['class_id'],
                'status' => $validated['status'],
                'remarks' => $validated['remarks'],
            ]
        );

        return redirect()->route('attendance.index')->with('success', 'Attendance marked successfully!');
    }

    /**
     * Display the specified attendance record.
     */
    public function show(Attendance $attendance)
    {
        $this->authorize('view', $attendance);

        $attendance->load('student.user', 'schoolClass');

        return view('attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified attendance record.
     */
    public function edit(Attendance $attendance)
    {
        $this->authorize('update', $attendance);

        $classes = $this->getAccessibleClasses();
        $students = Student::whereIn('class_id', $classes->pluck('id'))->with('user')->get();

        $attendance->load('student.user', 'schoolClass');

        return view('attendance.edit', compact('attendance', 'classes', 'students'));
    }

    /**
     * Update the specified attendance record in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $this->authorize('update', $attendance);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:school_classes,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:255',
        ]);

        $attendance->update($validated);

        return redirect()->route('attendance.show', $attendance)->with('success', 'Attendance updated successfully!');
    }

    /**
     * Remove the specified attendance record from storage.
     */
    public function destroy(Attendance $attendance)
    {
        $this->authorize('delete', $attendance);

        $attendance->delete();

        return redirect()->route('attendance.index')->with('success', 'Attendance record deleted successfully!');
    }

    /**
     * Generate attendance report.
     */
    public function report(Request $request)
    {
        $this->authorize('viewAny', Attendance::class);

        if (auth()->user()->role === 'student') {
            $student = auth()->user()->student;
            if (!$student) {
                return back()->with('error', 'Student profile not found.');
            }
            $classId = $student->class_id;
            $month = $request->get('month', now()->format('Y-m'));
        } else {
            $classId = $request->get('class_id');
            $month = $request->get('month', now()->format('Y-m'));
        }

        $query = Attendance::with('student.user');

        if (auth()->user()->role === 'teacher') {
            $teacher = auth()->user()->teacher;
            $allowed = $teacher ? $teacher->schoolClasses->pluck('id')->toArray() : [];
            if (!in_array($classId, $allowed)) {
                abort(403);
            }
        } elseif (auth()->user()->role === 'student') {
            $student = auth()->user()->student;
            if ($student && $student->class_id != $classId) {
                abort(403);
            }
            $query->where('student_id', $student->id);
        }

        $attendances = $query->where('class_id', $classId)
            ->where('attendance_date', 'like', $month . '%')
            ->get()
            ->groupBy('student_id');

        if (auth()->user()->role === 'student') {
            $student = auth()->user()->student;
            $students = Student::where('id', $student->id)->with('user')->get();
        } else {
            $students = Student::where('class_id', $classId)->with('user')->get();
        }

        // Calculate statistics
        $stats = [
            'total_records' => $attendances->sum(fn($recs) => $recs->count()),
            'total_present' => $attendances->sum(fn($recs) => $recs->where('status', 'present')->count()),
            'total_absent' => $attendances->sum(fn($recs) => $recs->where('status', 'absent')->count()),
            'total_late' => $attendances->sum(fn($recs) => $recs->where('status', 'late')->count()),
            'total_excused' => $attendances->sum(fn($recs) => $recs->where('status', 'excused')->count()),
        ];

        if ($request->has('export')) {
            $filename = 'attendance_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];
            $columns = ['Student', 'Date', 'Status', 'Remarks'];
            $callback = function() use ($attendances, $students, $columns) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, $columns);
                foreach ($students as $stu) {
                    $records = $attendances->get($stu->id, collect());
                    foreach ($records as $rec) {
                        fputcsv($handle, [
                            $stu->user->name ?? '',
                            $rec->attendance_date,
                            $rec->status,
                            $rec->remarks,
                        ]);
                    }
                }
                fclose($handle);
            };
            return response()->stream($callback, 200, $headers);
        }

        return view('attendance.report', compact('attendances', 'students', 'month', 'classId', 'stats'));
    }

    /**
     * Bulk attendance submission. Marks all students present except those explicitly listed absent.
     */
    public function bulk(Request $request)
    {
        $this->authorize('create', Attendance::class);

        $data = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'attendance_date' => 'required|date',
            'absent_ids' => 'array',
            'absent_ids.*' => 'exists:students,id',
        ]);

        $class = SchoolClass::findOrFail($data['class_id']);
        $studentIds = $class->students()->pluck('id')->toArray();

        foreach ($studentIds as $sid) {
            Attendance::updateOrCreate(
                ['student_id' => $sid, 'attendance_date' => $data['attendance_date']],
                [
                    'class_id' => $data['class_id'],
                    'status' => in_array($sid, $data['absent_ids'] ?? []) ? 'absent' : 'present',
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'Bulk attendance marked successfully!']);
    }

    /**
     * Get classes accessible to the current user.
     */
    private function getAccessibleClasses()
    {
        if (auth()->user()->role === 'admin') {
            return SchoolClass::with('students')->get();
        } elseif (auth()->user()->role === 'teacher') {
            $teacher = auth()->user()->teacher;
            return $teacher ? $teacher->schoolClasses()->with('students')->get() : collect();
        } else {
            $student = auth()->user()->student;
            return $student ? SchoolClass::where('id', $student->class_id)->with('students')->get() : collect();
        }
    }
}
