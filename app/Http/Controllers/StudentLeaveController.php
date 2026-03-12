<?php

namespace App\Http\Controllers;

use App\Models\StudentLeave;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class StudentLeaveController extends Controller
{
    /**
     * Display all student leaves with filtering
     */
    public function index(Request $request): View
    {
        $query = StudentLeave::with('student', 'approvedBy');

        // Students can only see their own leaves
        if (auth()->user()->role === 'student') {
            $studentId = auth()->user()->student?->id;
            if (!$studentId) {
                return back()->with('error', 'Student profile not found.');
            }
            $query->where('student_id', $studentId);
        } else {
            // Filter by student for admin/teachers
            if ($request->has('student_id') && $request->student_id) {
                $query->where('student_id', $request->student_id);
            }
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->has('leave_type') && $request->leave_type) {
            $query->where('leave_type', $request->leave_type);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('from_date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('to_date', '<=', $request->to_date);
        }

        $leaves = $query->orderBy('from_date', 'desc')->paginate(20);
        $students = auth()->user()->role === 'student' ? [] : Student::orderBy('name')->get();

        return view('student-leaves.index', [
            'leaves' => $leaves,
            'students' => $students,
            'leaveTypes' => ['medical', 'personal', 'casual', 'earned', 'unpaid'],
            'statuses' => ['pending', 'approved', 'rejected'],
        ]);
    }

    /**
     * Show create leave form
     */
    public function create(): View
    {
        if (Gate::denies('request_leave')) {
            abort(403, 'You are not authorized to request leaves.');
        }
        // Students can only create for themselves
        if (auth()->user()->role === 'student') {
            $student = auth()->user()->student;
            if (!$student) {
                return back()->with('error', 'Student profile not found.');
            }
            $students = [$student];
        } else {
            $students = Student::orderBy('name')->get();
        }

        return view('student-leaves.create', [
            'students' => $students,
            'isStudent' => auth()->user()->role === 'student',
            'leaveTypes' => [
                'medical' => 'Medical',
                'personal' => 'Personal',
                'casual' => 'Casual',
                'earned' => 'Earned',
                'unpaid' => 'Unpaid',
            ],
        ]);
    }

    /**
     * Store new student leave
     */
    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('request_leave')) {
            abort(403, 'You are not authorized to request leaves.');
        }
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'leave_type' => 'required|in:medical,personal,casual,earned,unpaid',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string|min:10',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'auto_attendance' => 'boolean',
        ]);

        // Students can only create leaves for themselves
        if (auth()->user()->role === 'student') {
            $studentId = auth()->user()->student?->id;
            if (!$studentId || $validated['student_id'] != $studentId) {
                return back()->with('error', 'You can only create leave requests for yourself.');
            }
        }

        // Check for overlapping leaves
        $existingLeave = StudentLeave::where('student_id', $validated['student_id'])
            ->where('status', 'approved')
            ->whereBetween('from_date', [$validated['from_date'], $validated['to_date']])
            ->orWhere(function ($query) use ($validated) {
                $query->where('student_id', $validated['student_id'])
                    ->where('status', 'approved')
                    ->whereBetween('to_date', [$validated['from_date'], $validated['to_date']]);
            })
            ->first();

        if ($existingLeave) {
            return back()->with('error', 'Student already has an approved leave during this period.');
        }

        // Calculate number of days
        $fromDate = new \DateTime($validated['from_date']);
        $toDate = new \DateTime($validated['to_date']);
        $numberOfDays = (int)$toDate->diff($fromDate)->format('%d') + 1;

        $validated['number_of_days'] = $numberOfDays;

        // Handle file upload
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('student-leaves', 'public');
            $validated['document_path'] = $path;
        }

        StudentLeave::create($validated);

        return redirect()->route('student-leaves.index')
            ->with('success', 'Leave request submitted successfully!');
    }

    /**
     * Show leave details
     */
    public function show(StudentLeave $studentLeave): View
    {
        $this->authorize('view', $studentLeave);
        return view('student-leaves.show', [
            'leave' => $studentLeave->load('student', 'approvedBy'),
        ]);
    }

    /**
     * Edit leave
     */
    public function edit(StudentLeave $studentLeave): View
    {
        // Authorize the user
        $this->authorize('update', $studentLeave);

        // Students can only edit their own leaves
        if (auth()->user()->role === 'student') {
            $students = [$studentLeave->student];
        } else {
            $students = Student::orderBy('name')->get();
        }

        return view('student-leaves.edit', [
            'leave' => $studentLeave,
            'students' => $students,
            'isStudent' => auth()->user()->role === 'student',
            'leaveTypes' => [
                'medical' => 'Medical',
                'personal' => 'Personal',
                'casual' => 'Casual',
                'earned' => 'Earned',
                'unpaid' => 'Unpaid',
            ],
        ]);
    }

    /**
     * Update leave
     */
    public function update(Request $request, StudentLeave $studentLeave): RedirectResponse
    {
        // Authorize the user
        $this->authorize('update', $studentLeave);

        // Only pending leaves can be edited
        if ($studentLeave->status !== 'pending') {
            return back()->with('error', 'Only pending leaves can be edited.');
        }

        // Students can only update their own leaves
        if (auth()->user()->role === 'student') {
            $studentId = auth()->user()->student?->id;
            if (!$studentId || $studentLeave->student_id != $studentId) {
                return back()->with('error', 'You can only edit your own leave requests.');
            }
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'leave_type' => 'required|in:medical,personal,casual,earned,unpaid',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string|min:10',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'auto_attendance' => 'boolean',
        ]);

        // Calculate number of days
        $fromDate = new \DateTime($validated['from_date']);
        $toDate = new \DateTime($validated['to_date']);
        $numberOfDays = (int)$toDate->diff($fromDate)->format('%d') + 1;

        $validated['number_of_days'] = $numberOfDays;

        // Handle file upload
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('student-leaves', 'public');
            $validated['document_path'] = $path;
        }

        $studentLeave->update($validated);

        return redirect()->route('student-leaves.show', $studentLeave)
            ->with('success', 'Leave updated successfully!');
    }

    /**
     * Delete leave (only pending)
     */
    public function destroy(StudentLeave $studentLeave): RedirectResponse
    {
        $this->authorize('delete', $studentLeave);

        if (Gate::denies('admin_only') && auth()->user()->student?->id !== $studentLeave->student_id) {
            abort(403, 'You are not authorized to delete this leave request.');
        }

        if ($studentLeave->status !== 'pending') {
            return back()->with('error', 'Only pending leaves can be deleted.');
        }

        // Students can only delete their own leaves
        if (auth()->user()->role === 'student') {
            $studentId = auth()->user()->student?->id;
            if (!$studentId || $studentLeave->student_id != $studentId) {
                return back()->with('error', 'You can only delete your own leave requests.');
            }
        }

        $studentLeave->delete();

        return redirect()->route('student-leaves.index')
            ->with('success', 'Leave request deleted!');
    }

    /**
     * Approve leave
     */
    public function approve(Request $request, StudentLeave $studentLeave): RedirectResponse
    {
        if (Gate::denies('approve_leaves')) {
            abort(403, 'You are not authorized to approve leaves.');
        }
        $validated = $request->validate([
            'remarks' => 'nullable|string',
        ]);

        $studentLeave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Auto-mark attendance as "On Leave" if enabled
        if ($studentLeave->auto_attendance) {
            $this->markAttendanceOnLeave($studentLeave);
        }

        return back()->with('success', 'Leave approved successfully!');
    }

    /**
     * Reject leave
     */
    public function reject(Request $request, StudentLeave $studentLeave): RedirectResponse
    {
        if (Gate::denies('approve_leaves')) {
            abort(403, 'You are not authorized to reject leaves.');
        }
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:5',
        ]);

        $studentLeave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Leave rejected!');
    }

    /**
     * Mark attendance as "On Leave" for leave period
     */
    private function markAttendanceOnLeave(StudentLeave $studentLeave): void
    {
        $fromDate = $studentLeave->from_date;
        $toDate = $studentLeave->to_date;

        $current = clone $fromDate;

        while ($current <= $toDate) {
            // Skip weekends if needed
            if ($current->format('N') <= 5) { // 1-5 is Mon-Fri
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentLeave->student_id,
                        'attendance_date' => $current->format('Y-m-d'),
                    ],
                    [
                        'status' => 'on_leave',
                        'remarks' => 'Auto-marked: ' . $studentLeave->leave_type . ' leave',
                    ]
                );
            }

            $current->modify('+1 day');
        }
    }

    /**
     * Dashboard showing leave statistics
     */
    public function dashboard(): View
    {
        $baseQuery = StudentLeave::query();

        // Students can only see their own data
        if (auth()->user()->role === 'student') {
            $studentId = auth()->user()->student?->id;
            if (!$studentId) {
                return back()->with('error', 'Student profile not found.');
            }
            $baseQuery->where('student_id', $studentId);
        }

        $stats = [
            'total_pending' => (clone $baseQuery)->pending()->count(),
            'total_approved' => (clone $baseQuery)->approved()->count(),
            'total_rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'currently_on_leave' => (clone $baseQuery)->active()->count(),
        ];

        $recentLeaves = (clone $baseQuery)
            ->with('student')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $leavesByType = (clone $baseQuery)
            ->approved()
            ->groupBy('leave_type')
            ->selectRaw('leave_type, count(*) as count')
            ->get();

        return view('student-leaves.dashboard', [
            'stats' => $stats,
            'recentLeaves' => $recentLeaves,
            'leavesByType' => $leavesByType,
            'isStudent' => auth()->user()->role === 'student',
        ]);
    }
}
