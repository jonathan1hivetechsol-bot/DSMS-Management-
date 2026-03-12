<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('title')->get();
        return view('books.index', compact('books'));
    }

    public function create()
    {
        if (Gate::denies('manage_library')) {
            abort(403, 'You are not authorized to add books.');
        }
        return view('books.create');
    }

    public function store(Request $request)
    {
        if (Gate::denies('manage_library')) {
            abort(403, 'You are not authorized to add books.');
        }
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'nullable|string',
            'isbn' => 'nullable|string|unique:books,isbn',
            'copies' => 'required|integer|min:0',
        ]);

        Book::create($data);
        return redirect()->route('books.index')->with('success', 'Book added.');
    }

    public function edit(Book $book)
    {
        if (Gate::denies('manage_library')) {
            abort(403, 'You are not authorized to edit books.');
        }
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        if (Gate::denies('manage_library')) {
            abort(403, 'You are not authorized to update books.');
        }
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'nullable|string',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'copies' => 'required|integer|min:0',
        ]);

        $book->update($data);
        return redirect()->route('books.index')->with('success', 'Book updated.');
    }

    public function destroy(Book $book)
    {
        if (Gate::denies('admin_only')) {
            abort(403, 'Only administrators can delete books.');
        }
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book removed.');
    }
}