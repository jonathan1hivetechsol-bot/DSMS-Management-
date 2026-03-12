<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        if (Gate::denies('create_announcements')) {
            abort(403, 'You are not authorized to create announcements.');
        }
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        if (Gate::denies('create_announcements')) {
            abort(403, 'You are not authorized to create announcements.');
        }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'important' => 'boolean',
        ]);
        Announcement::create($data);
        return redirect()->route('announcements.index')->with('success', 'Announcement posted.');
    }

    public function edit(Announcement $announcement)
    {
        if (Gate::denies('manage_announcements')) {
            abort(403, 'You are not authorized to edit announcements.');
        }
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        if (Gate::denies('manage_announcements')) {
            abort(403, 'You are not authorized to update announcements.');
        }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'important' => 'boolean',
        ]);
        $announcement->update($data);
        return redirect()->route('announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete announcements.');
        }
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Announcement deleted.');
    }
}