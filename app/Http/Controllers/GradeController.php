<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GradeController extends Controller
{
    public function index()
    {
        $query = Grade::with('student');

        if (auth()->user()->role === 'teacher') {
            // Teachers can see grades for all students
        } elseif (auth()->user()->role === 'student') {
            $student = auth()->user()->student;
            if ($student) {
                $query->where('student_id', $student->id);
            }
        }

        $grades = $query->latest();
        if (request()->has('export')) {
            $filename = 'grades_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ];
            $columns = ['Student','Subject','Marks Obtained','Total Marks','Percentage','Grade','Term','Exam Type','Remarks'];

            $callback = function() use ($grades, $columns) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, $columns);
                $grades->chunk(100)->each(function($chunk) use ($handle) {
                    foreach ($chunk as $grade) {
                        fputcsv($handle, [
                            $grade->student->user->name ?? '',
                            $grade->subject,
                            $grade->marks_obtained,
                            $grade->total_marks,
                            $grade->percentage . '%',
                            $grade->grade,
                            $grade->term,
                            $grade->exam_type,
                            $grade->remarks,
                        ]);
                    }
                });
                fclose($handle);
            };
            return response()->stream($callback, 200, $headers);
        }

        $grades = $grades->paginate(15);
        return view('grades.index', compact('grades'));
    }

    public function create()
    {
        if (Gate::denies('manage_grades')) {
            abort(403, 'You are not authorized to create grades.');
        }
        
        $students = \App\Models\Student::all();
        return view('grades.create', compact('students'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('manage_grades')) {
            abort(403, 'You are not authorized to create grades.');
        }
        
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string|max:50',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:0',
            'term' => 'required|string|max:20',
            'exam_type' => 'required|in:midterm,final,quiz,assignment',
            'remarks' => 'nullable|string',
        ]);
        Grade::create($data);
        return redirect()->route('grades.index')->with('success', 'Grade recorded successfully.');
    }

    public function edit(Grade $grade)
    {
        if (Gate::denies('manage_grades')) {
            abort(403, 'You are not authorized to edit grades.');
        }
        
        $students = \App\Models\Student::all();
        return view('grades.edit', compact('grade', 'students'));
    }

    public function update(Request $request, Grade $grade)
    {
        if (Gate::denies('manage_grades')) {
            abort(403, 'You are not authorized to update grades.');
        }
        
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string|max:50',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:0',
            'term' => 'required|string|max:20',
            'exam_type' => 'required|in:midterm,final,quiz,assignment',
            'remarks' => 'nullable|string',
        ]);
        $grade->update($data);
        return redirect()->route('grades.index')->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete grades.');
        }
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Grade removed.');
    }
}