<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::latest()->paginate(15);
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can create subjects.');
        }
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can create subjects.');
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        Subject::create($data);
        return redirect()->route('subjects.index')->with('success', 'Subject added.');
    }

    public function edit(Subject $subject)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can edit subjects.');
        }
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can update subjects.');
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $subject->update($data);
        return redirect()->route('subjects.index')->with('success', 'Subject updated.');
    }

    public function destroy(Subject $subject)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete subjects.');
        }
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject removed.');
    }
}
