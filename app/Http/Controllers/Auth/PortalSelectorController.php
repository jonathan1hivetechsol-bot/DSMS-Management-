<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalSelectorController extends Controller
{
    /**
     * Show the portal selector view.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('auth.portal-selector');
    }

    /**
     * Select and store the chosen portal in session.
     *
     * @param  Request  $request
     * @param  string   $portal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function select(Request $request, $portal)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $allowedPortals = [];

        // Determine which portals the user can access
        if ($user->role === 'admin') {
            $allowedPortals = ['admin'];
        } elseif ($user->role === 'teacher') {
            $allowedPortals = ['teacher'];
        } elseif ($user->role === 'student') {
            $allowedPortals = ['student'];
        }

        // Validate that the selected portal is allowed for this user
        if (!in_array($portal, $allowedPortals)) {
            return redirect()->route('second', ['dashboards', 'analytics'])->with('error', 'You do not have access to this portal.');
        }

        // Store the selected portal in the session
        session(['selected_portal' => $portal]);

        return redirect()->route('second', ['dashboards', 'analytics']);
    }

    /**
     * Clear the selected portal from session (logout).
     *
     * @return void
     */
    public static function clearPortal()
    {
        session()->forget('selected_portal');
    }
}
