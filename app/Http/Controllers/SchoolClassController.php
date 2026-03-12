<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = SchoolClass::with('teacher.user', 'students')->get();
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::denies('manage_classes')) {
            abort(403, 'You are not authorized to create classes.');
        }
        $teachers = Teacher::with('user')->get();
        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Gate::denies('manage_classes')) {
            abort(403, 'You are not authorized to create classes.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:school_classes,name',
            'teacher_id' => 'required|exists:teachers,id',
            'capacity' => 'required|integer|min:1|max:200',
        ]);

        SchoolClass::create($validated);

        return redirect()->route('classes.index')->with('success', 'Class added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolClass $class)
    {
        $class->load('teacher.user', 'students.user');
        return view('classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $class)
    {
        if (Gate::denies('manage_classes')) {
            abort(403, 'You are not authorized to edit classes.');
        }
        $class->load('teacher');
        $teachers = Teacher::with('user')->get();
        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        if (Gate::denies('manage_classes')) {
            abort(403, 'You are not authorized to update classes.');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:school_classes,name,' . $class->id,
            'teacher_id' => 'required|exists:teachers,id',
            'capacity' => 'required|integer|min:1|max:200',
        ]);

        $class->update($validated);

        return redirect()->route('classes.show', $class)->with('success', 'Class updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $class)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete classes.');
        }
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully!');
    }
}
