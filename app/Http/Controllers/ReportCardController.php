<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReportCardController extends Controller
{
    public function show(Student $student)
    {
        // Ensure permission: only admin/teacher or the student himself
        $user = auth()->user();
        if ($user->role === 'student' && $user->student->id !== $student->id) {
            abort(403, 'You can only view your own report card.');
        } elseif (Gate::denies('view_all_grades') && $user->role !== 'student') {
            abort(403, 'You are not authorized to view report cards.');
        }

        $grades = $student->grades()->get()->groupBy('term');
        $attendance = $student->attendances()->whereYear('date', now()->year)->get();
        
        $attendancePercentage = $attendance->count() > 0 
            ? (($attendance->where('status', 'present')->count() / $attendance->count()) * 100)
            : 0;

        return view('reports.card', compact('student', 'grades', 'attendance', 'attendancePercentage'));
    }

    public function pdf(Student $student)
    {
        // Duplicate permission checks from show
        $user = auth()->user();
        if ($user->role === 'student' && $user->student->id !== $student->id) {
            abort(403, 'You can only view your own report card.');
        } elseif (Gate::denies('view_all_grades') && $user->role !== 'student') {
            abort(403, 'You are not authorized to view report cards.');
        }

        return PDFService::generateReportCard($student);
    }
}