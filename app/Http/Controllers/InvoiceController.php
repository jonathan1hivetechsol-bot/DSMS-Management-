<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Student;
use App\Models\SchoolClass;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('student', 'schoolClass');
        if ($request->has('student_id')) {
            $query->where('student_id', $request->get('student_id'));
        }
        if ($request->has('class_id')) {
            $query->where('class_id', $request->get('class_id'));
        }

        if (auth()->user()->role === 'teacher') {
            $teacher = auth()->user()->teacher;
            if ($teacher) {
                $classIds = $teacher->schoolClasses->pluck('id')->toArray();
                $query->whereIn('class_id', $classIds);
            }
        } elseif (auth()->user()->role === 'student') {
            $student = auth()->user()->student;
            if ($student) {
                $query->where('student_id', $student->id);
            }
        }

        $invoices = $query->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        if (Gate::denies('manage_invoices')) {
            abort(403, 'You are not authorized to create invoices.');
        }
        
        $students = Student::all();
        $classes = SchoolClass::all();
        return view('invoices.create', compact('students', 'classes'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('manage_invoices')) {
            abort(403, 'You are not authorized to create invoices.');
        }
        
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'nullable|exists:school_classes,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        Invoice::create($data);
        return redirect()->route('invoices.index')->with('success', 'Invoice created.');
    }

    public function edit(Invoice $invoice)
    {
        if (Gate::denies('manage_invoices')) {
            abort(403, 'You are not authorized to edit invoices.');
        }
        
        $students = Student::all();
        $classes = SchoolClass::all();
        return view('invoices.edit', compact('invoice', 'students', 'classes'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if (Gate::denies('manage_invoices')) {
            abort(403, 'You are not authorized to update invoices.');
        }
        
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'nullable|exists:school_classes,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $invoice->update($data);
        return redirect()->route('invoices.index')->with('success', 'Invoice updated.');
    }

    public function destroy(Invoice $invoice)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete invoices.');
        }
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice removed.');
    }

    public function markPaid(Invoice $invoice)
    {
        if (Gate::denies('manage_invoices')) {
            abort(403, 'You are not authorized to mark invoices as paid.');
        }
        $invoice->markPaid();
        return redirect()->back()->with('success', 'Invoice marked paid.');
    }

    public function pdf(Invoice $invoice)
    {
        // permission check similar to index
        $user = auth()->user();
        if ($user->role === 'teacher') {
            $teacher = $user->teacher;
            if ($teacher && !$teacher->schoolClasses->pluck('id')->contains($invoice->class_id)) {
                abort(403);
            }
        } elseif ($user->role === 'student' && $user->student->id !== $invoice->student_id) {
            abort(403);
        }

        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download('invoice_'.$invoice->id.'.pdf');
    }
}