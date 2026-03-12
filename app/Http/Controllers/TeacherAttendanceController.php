<?php

namespace App\Http\Controllers;

use App\Models\TeacherAttendance;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class TeacherAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $teachers = Teacher::with('user')->get();
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $attendances = TeacherAttendance::byMonth($year, $month)
            ->with('teacher.user')
            ->orderBy('attendance_date')
            ->get();

        $stats = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'leave' => $attendances->where('status', 'leave')->count(),
        ];

        return view('teacher-attendance.index', compact('attendances', 'teachers', 'year', 'month', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::denies('manage_teacher_attendance')) {
            abort(403, 'You are not authorized to mark teacher attendance.');
        }
        $teachers = Teacher::with('user')->get();
        $today = now()->toDateString();
        return view('teacher-attendance.create', compact('teachers', 'today'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Gate::denies('manage_teacher_attendance')) {
            abort(403, 'You are not authorized to mark teacher attendance.');
        }
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,leave',
            'leave_type' => 'nullable|in:medical,casual,earned,unpaid',
            'remarks' => 'nullable|string',
        ]);

        // Check if record already exists
        $existing = TeacherAttendance::where([
            'teacher_id' => $validated['teacher_id'],
            'attendance_date' => $validated['attendance_date'],
        ])->first();

        if ($existing) {
            $existing->update($validated);
            return redirect()->route('teacher-attendance.index')
                ->with('success', 'Teacher attendance updated successfully.');
        }

        TeacherAttendance::create($validated);

        return redirect()->route('teacher-attendance.index')
            ->with('success', 'Teacher attendance marked successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TeacherAttendance $teacherAttendance)
    {
        return view('teacher-attendance.show', compact('teacherAttendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeacherAttendance $teacherAttendance)
    {
        if (Gate::denies('manage_teacher_attendance')) {
            abort(403, 'You are not authorized to edit teacher attendance.');
        }
        $teachers = Teacher::with('user')->get();
        return view('teacher-attendance.edit', compact('teacherAttendance', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeacherAttendance $teacherAttendance)
    {
        if (Gate::denies('manage_teacher_attendance')) {
            abort(403, 'You are not authorized to update teacher attendance.');
        }
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,leave',
            'leave_type' => 'nullable|in:medical,casual,earned,unpaid',
            'remarks' => 'nullable|string',
        ]);

        $teacherAttendance->update($validated);

        return redirect()->route('teacher-attendance.index')
            ->with('success', 'Teacher attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeacherAttendance $teacherAttendance)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete teacher attendance records.');
        }
        $teacherAttendance->delete();
        return redirect()->route('teacher-attendance.index')
            ->with('success', 'Teacher attendance deleted successfully.');
    }

    /**
     * Bulk mark attendance
     */
    public function bulk(Request $request)
    {
        if (Gate::denies('manage_teacher_attendance')) {
            abort(403, 'You are not authorized to perform bulk attendance marking.');
        }
        $validated = $request->validate([
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.teacher_id' => 'required|exists:teachers,id',
            'attendances.*.status' => 'required|in:present,absent,late,leave',
            'attendances.*.leave_type' => 'nullable|in:medical,casual,earned,unpaid',
        ]);

        $date = $validated['attendance_date'];

        foreach ($validated['attendances'] as $attendance) {
            TeacherAttendance::updateOrCreate(
                [
                    'teacher_id' => $attendance['teacher_id'],
                    'attendance_date' => $date,
                ],
                $attendance
            );
        }

        return redirect()->route('teacher-attendance.index')
            ->with('success', 'Bulk attendance marked successfully.');
    }

    /**
     * Generate attendance report for a teacher
     */
    public function report(Request $request)
    {
        $teachers = Teacher::with('user')->get();
        $teacherId = $request->get('teacher_id');
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $query = TeacherAttendance::query();

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        $attendances = $query->byMonth($year, $month)
            ->with('teacher.user')
            ->orderBy('attendance_date')
            ->get();

        $stats = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'leave' => $attendances->where('status', 'leave')->count(),
            'total_days' => $attendances->count(),
        ];

        return view('teacher-attendance.report', compact('attendances', 'teachers', 'year', 'month', 'stats', 'teacherId'));
    }
}
