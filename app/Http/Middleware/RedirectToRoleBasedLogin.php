<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectToRoleBasedLogin
{
    /**
     * Handle an incoming request.
     * Redirects unauthenticated users from generic /login to role-based login
     */
    public function handle(Request $request, Closure $next)
    {
        // If already authenticated, continue
        if (Auth::check()) {
            return $next($request);
        }

        // If route is /login, show portal selection to choose role
        if ($request->path() === 'login') {
            return redirect()->route('portal.selector.view');
        }

        return $next($request);
    }
}
