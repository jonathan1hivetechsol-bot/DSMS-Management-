<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentLoginController extends Controller
{
    /**
     * Display the student login view.
     * Only students can access this route.
     */
    public function show(): View
    {
        // If user is authenticated and not student, redirect to dashboard
        if (Auth::check() && Auth::user()->role !== 'student') {
            return redirect()->route('second', ['dashboards', 'analytics']);
        }

        return view('auth.student-login');
    }

    /**
     * Handle an incoming authentication request for student.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $user = Auth::user();

        // Verify user is student
        if ($user->role !== 'student') {
            Auth::logout();
            $request->session()->invalidate();
            return back()->withErrors(['email' => 'Unauthorized access. Student credentials required.']);
        }

        $request->session()->regenerate();
        
        // Log student login attempt
        \Log::info('Student login successful', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->route('second', ['dashboards', 'analytics']);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            \Log::info('Student logout', ['user_id' => $user->id, 'email' => $user->email]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
