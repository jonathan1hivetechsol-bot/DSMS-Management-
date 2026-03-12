<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only admin and teachers can view all students
        if (Gate::allows('view_all_students')) {
            // Admin or teacher - view all students
            $students = Student::with('user', 'schoolClass')->get();
        } else {
            // Student - can only view themselves
            $student = auth()->user()->student;
            if (!$student) {
                abort(403, 'Unauthorized');
            }
            $students = collect([$student]);
        }
        
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin and teachers can create students
        if (Gate::denies('manage_students')) {
            abort(403, 'Only admins and teachers can create students.');
        }
        
        $classes = SchoolClass::all();
        return view('students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admin and teachers can create students
        if (Gate::denies('manage_students')) {
            abort(403, 'Only admins and teachers can create students.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'student_id' => 'required|string|unique:students,student_id',
            'date_of_birth' => 'required|date',
            'class_id' => 'required|exists:school_classes,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',
        ]);

        // Create student record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $validated['student_id'],
            'date_of_birth' => $validated['date_of_birth'],
            'class_id' => $validated['class_id'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'parent_name' => $validated['parent_name'],
            'parent_phone' => $validated['parent_phone'],
        ]);

        return redirect()->route('students.index')->with('success', 'Student added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Admin/Teacher can see any student, students can only see themselves
        if (Gate::allows('view_all_students')) {
            // Admin or teacher - can view anyone
        } else if (Gate::allows('student_only')) {
            // Student - can only view themselves
            if (auth()->user()->id !== $student->user_id) {
                abort(403, 'You can only view your own profile.');
            }
        } else {
            abort(403, 'Unauthorized');
        }
        
        $student->load('user', 'schoolClass');
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        // Only admin and teachers can edit  students
        if (Gate::denies('manage_students')) {
            abort(403, 'Only admins and teachers can edit students.');
        }
        
        $student->load('user', 'schoolClass');
        $classes = SchoolClass::all();
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        // Only admin and teachers can update students
        if (Gate::denies('manage_students')) {
            abort(403, 'Only admins and teachers can update students.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'date_of_birth' => 'required|date',
            'class_id' => 'required|exists:school_classes,id',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string',
        ]);

        // Update user
        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update student
        $student->update([
            'student_id' => $validated['student_id'],
            'date_of_birth' => $validated['date_of_birth'],
            'class_id' => $validated['class_id'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'parent_name' => $validated['parent_name'],
            'parent_phone' => $validated['parent_phone'],
        ]);

        return redirect()->route('students.show', $student)->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Only admins can delete students
        if (Gate::denies('admin_only')) {
            abort(403, 'Only admins can delete students.');
        }
        
        $userId = $student->user_id;
        $student->delete();
        User::find($userId)->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
    }
}
