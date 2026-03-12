<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     * DSMS: Redirect to portal-based authentication instead
     * Users should see portal selection first, not the generic login page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // Redirect to portal selection (role-based authentication flow)
        return redirect()->route('portal.selector.view');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('second', ['dashboards', 'analytics']);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Clear the selected portal from session
        \App\Http\Controllers\Auth\PortalSelectorController::clearPortal();

        return redirect('/auth/logout');
    }
}
