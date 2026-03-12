<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    /**
     * Display the admin login view.
     * Only admins can access this route.
     */
    public function show(): View
    {
        // If user is authenticated and not admin, redirect to dashboard
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return redirect()->route('second', ['dashboards', 'analytics']);
        }

        return view('auth.admin-login');
    }

    /**
     * Handle an incoming authentication request for admin.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $user = Auth::user();

        // Verify user is admin
        if ($user->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            return back()->withErrors(['email' => 'Unauthorized access. Admin credentials required.']);
        }

        $request->session()->regenerate();
        
        // Log admin login attempt
        \Log::info('Admin login successful', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->route('second', ['dashboards', 'analytics']);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            \Log::info('Admin logout', ['user_id' => $user->id, 'email' => $user->email]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
