<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with('book', 'student')->orderBy('loaned_at', 'desc');
        
        // Students can only see their own loans
        if (auth()->user()->role === 'student') {
            $student = auth()->user()->student;
            if ($student) {
                $query->where('student_id', $student->id);
            }
        } elseif ($request->filled('student_id')) {
            // Only admins/teachers can filter by other students
            $query->where('student_id', $request->student_id);
        }
        
        $loans = $query->get();
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        if (Gate::denies('manage_library')) {
            abort(403, 'You are not authorized to create book loans.');
        }
        
        $books = Book::where('copies', '>', 0)->orderBy('title')->get();
        $students = Student::orderBy('name')->get();
        return view('loans.create', compact('books', 'students'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('manage_library')) {
            abort(403, 'You are not authorized to create book loans.');
        }
        
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'student_id' => 'required|exists:students,id',
            'loaned_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:loaned_at',
        ]);

        $loan = Loan::create($data);
        // decrement copies
        $loan->book->decrement('copies');

        return redirect()->route('loans.index')->with('success', 'Book loan recorded.');
    }

    public function return(Loan $loan)
    {
        if (Gate::denies('manage_library')) {
            abort(403, 'You are not authorized to mark book returns.');
        }
        if (!$loan->returned_at) {
            $loan->update(['returned_at' => now()->toDateString()]);
            $loan->book->increment('copies');
        }

        return redirect()->route('loans.index')->with('success', 'Book marked returned.');
    }

    public function destroy(Loan $loan)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete loan records.');
        }
        // if not returned yet, restore copies
        if (!$loan->returned_at) {
            $loan->book->increment('copies');
        }
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan entry removed.');
    }
}