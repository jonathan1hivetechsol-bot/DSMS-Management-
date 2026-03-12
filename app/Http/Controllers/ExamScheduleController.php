<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExamScheduleController extends Controller
{
    public function index()
    {
        $schedules = ExamSchedule::with('subject', 'schoolClass')->latest()->paginate(15);
        return view('exams.index', compact('schedules'));
    }

    public function create()
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can create exam schedules.');
        }
        $subjects = Subject::all();
        $classes = SchoolClass::all();
        return view('exams.create', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can create exam schedules.');
        }
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:school_classes,id',
            'exam_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        ExamSchedule::create($data);
        return redirect()->route('exam-schedules.index')->with('success', 'Exam scheduled.');
    }

    public function edit(ExamSchedule $examSchedule)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can edit exam schedules.');
        }
        $subjects = Subject::all();
        $classes = SchoolClass::all();
        return view('exams.edit', compact('examSchedule', 'subjects', 'classes'));
    }

    public function update(Request $request, ExamSchedule $examSchedule)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can update exam schedules.');
        }
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:school_classes,id',
            'exam_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $examSchedule->update($data);
        return redirect()->route('exam-schedules.index')->with('success', 'Exam schedule updated.');
    }

    public function destroy(ExamSchedule $examSchedule)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete exam schedules.');
        }
        $examSchedule->delete();
        return redirect()->route('exam-schedules.index')->with('success', 'Exam schedule removed.');
    }
}
