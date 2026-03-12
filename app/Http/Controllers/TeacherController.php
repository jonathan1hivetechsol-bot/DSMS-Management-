<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Teacher::class);
        $teachers = Teacher::with('user')->get();
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Teacher::class);
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Teacher::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'teacher_id' => 'required|string|unique:teachers,teacher_id',
            'subject' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'teacher',
        ]);

        // Create teacher record
        Teacher::create([
            'user_id' => $user->id,
            'teacher_id' => $validated['teacher_id'],
            'subject' => $validated['subject'],
            'qualification' => $validated['qualification'],
            'hire_date' => $validated['hire_date'],
            'salary' => $validated['salary'],
        ]);

        return redirect()->route('teachers.index')->with('success', 'Teacher added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('user');
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $this->authorize('update', $teacher);
        $teacher->load('user');
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $this->authorize('update', $teacher);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'teacher_id' => 'required|string|unique:teachers,teacher_id,' . $teacher->id,
            'subject' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        // Update user
        $teacher->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update teacher
        $teacher->update([
            'teacher_id' => $validated['teacher_id'],
            'subject' => $validated['subject'],
            'qualification' => $validated['qualification'],
            'hire_date' => $validated['hire_date'],
            'salary' => $validated['salary'],
        ]);

        return redirect()->route('teachers.show', $teacher)->with('success', 'Teacher updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $this->authorize('delete', $teacher);
        $userId = $teacher->user_id;
        $teacher->delete();
        User::find($userId)->delete();

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully!');
    }
}
