<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeacherLoginController extends Controller
{
    /**
     * Display the teacher login view.
     * Only teachers can access this route.
     */
    public function show(): View
    {
        // If user is authenticated and not teacher, redirect to dashboard
        if (Auth::check() && Auth::user()->role !== 'teacher') {
            return redirect()->route('second', ['dashboards', 'analytics']);
        }

        return view('auth.teacher-login');
    }

    /**
     * Handle an incoming authentication request for teacher.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $user = Auth::user();

        // Verify user is teacher
        if ($user->role !== 'teacher') {
            Auth::logout();
            $request->session()->invalidate();
            return back()->withErrors(['email' => 'Unauthorized access. Teacher credentials required.']);
        }

        $request->session()->regenerate();
        
        // Log teacher login attempt
        \Log::info('Teacher login successful', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->route('second', ['dashboards', 'analytics']);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            \Log::info('Teacher logout', ['user_id' => $user->id, 'email' => $user->email]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
