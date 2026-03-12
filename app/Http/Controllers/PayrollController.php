<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $payrolls = Payroll::byMonth($year, $month)
            ->with('teacher.user')
            ->orderBy('teacher_id')
            ->get();

        $stats = [
            'total_amount' => $payrolls->sum('net_salary'),
            'pending' => $payrolls->where('status', 'pending')->count(),
            'approved' => $payrolls->where('status', 'approved')->count(),
            'paid' => $payrolls->where('status', 'paid')->count(),
        ];

        return view('payroll.index', compact('payrolls', 'year', 'month', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (Gate::denies('manage_payroll')) {
            abort(403, 'You are not authorized to create payroll.');
        }
        $teachers = Teacher::with('user')->get();
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        return view('payroll.create', compact('teachers', 'year', 'month'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Gate::denies('manage_payroll')) {
            abort(403, 'You are not authorized to create payroll.');
        }
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'base_salary' => 'required|numeric|min:0',
            'working_days' => 'required|integer|min:1',
            'present_days' => 'required|integer|min:0',
            'absent_days' => 'required|integer|min:0',
            'leave_days' => 'required|integer|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Check if payroll already exists for the month
        $existing = Payroll::where([
            'teacher_id' => $validated['teacher_id'],
            'year' => $validated['year'],
            'month' => $validated['month'],
        ])->first();

        if ($existing) {
            return back()->with('error', 'Payroll already exists for this month.');
        }

        $payroll = new Payroll($validated);
        $payroll->deductions = $validated['deductions'] ?? 0;
        $payroll->allowances = $validated['allowances'] ?? 0;

        // Calculate gross and net salary
        $perDayRate = $payroll->base_salary / $payroll->working_days;
        $payroll->gross_salary = ($perDayRate * $payroll->present_days) + $payroll->allowances;
        $payroll->net_salary = $payroll->gross_salary - $payroll->deductions;

        $payroll->save();

        return redirect()->route('payroll.index', ['year' => $validated['year'], 'month' => $validated['month']])
            ->with('success', 'Payroll created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll)
    {
        $payroll->load('teacher.user');
        return view('payroll.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        if (Gate::denies('manage_payroll')) {
            abort(403, 'You are not authorized to edit payroll.');
        }
        $teachers = Teacher::with('user')->get();
        return view('payroll.edit', compact('payroll', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
        if (Gate::denies('manage_payroll')) {
            abort(403, 'You are not authorized to update payroll.');
        }
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'base_salary' => 'required|numeric|min:0',
            'working_days' => 'required|integer|min:1',
            'present_days' => 'required|integer|min:0',
            'absent_days' => 'required|integer|min:0',
            'leave_days' => 'required|integer|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,approved,paid',
            'notes' => 'nullable|string',
        ]);

        $payroll->fill($validated);
        $payroll->deductions = $validated['deductions'] ?? 0;
        $payroll->allowances = $validated['allowances'] ?? 0;

        // Recalculate gross and net salary
        $perDayRate = $payroll->base_salary / $payroll->working_days;
        $payroll->gross_salary = ($perDayRate * $payroll->present_days) + $payroll->allowances;
        $payroll->net_salary = $payroll->gross_salary - $payroll->deductions;

        $payroll->save();

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'Payroll updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete payroll.');
        }
        if ($payroll->status === 'paid') {
            return back()->with('error', 'Cannot delete paid payroll.');
        }

        $payroll->delete();
        return redirect()->route('payroll.index')
            ->with('success', 'Payroll deleted successfully.');
    }

    /**
     * Approve payroll
     */
    public function approve(Payroll $payroll)
    {
        if (Gate::denies('manage_payroll')) {
            abort(403, 'You are not authorized to approve payroll.');
        }
        if ($payroll->status !== 'pending') {
            return back()->with('error', 'Only pending payroll can be approved.');
        }

        $payroll->update(['status' => 'approved']);

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'Payroll approved successfully.');
    }

    /**
     * Mark payroll as paid
     */
    public function markPaid(Payroll $payroll)
    {
        if (Gate::denies('manage_payroll')) {
            abort(403, 'You are not authorized to mark payroll as paid.');
        }
        if ($payroll->status !== 'approved') {
            return back()->with('error', 'Only approved payroll can be marked as paid.');
        }

        $payroll->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'Payroll marked as paid successfully.');
    }

    /**
     * Generate payroll for all teachers
     */
    public function generate(Request $request)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can generate payroll.');
        }
        $validated = $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $teachers = Teacher::all();
        $date = Carbon::createFromDate($validated['year'], $validated['month'], 1);
        $daysInMonth = $date->daysInMonth;

        foreach ($teachers as $teacher) {
            // Check if already exists
            $existing = Payroll::where([
                'teacher_id' => $teacher->id,
                'year' => $validated['year'],
                'month' => $validated['month'],
            ])->exists();

            if ($existing) {
                continue;
            }

            // Get attendance for the month
            $attendance = TeacherAttendance::byMonth($validated['year'], $validated['month'])
                ->where('teacher_id', $teacher->id)
                ->get();

            $presentDays = $attendance->where('status', 'present')->count();
            $leaveDays = $attendance->where('status', 'leave')->count();
            $absentDays = $attendance->where('status', 'absent')->count();

            // Create payroll
            $perDayRate = $teacher->salary / 26; // Assuming 26 working days per month
            $grossSalary = ($perDayRate * $presentDays) + ($leaveDays > 0 ? $perDayRate * min($leaveDays, 5) : 0);

            Payroll::create([
                'teacher_id' => $teacher->id,
                'year' => $validated['year'],
                'month' => $validated['month'],
                'base_salary' => $teacher->salary,
                'working_days' => 26,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'leave_days' => $leaveDays,
                'deductions' => 0,
                'allowances' => 0,
                'gross_salary' => $grossSalary,
                'net_salary' => $grossSalary,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('payroll.index', ['year' => $validated['year'], 'month' => $validated['month']])
            ->with('success', 'Payroll generated successfully for all teachers.');
    }
}
