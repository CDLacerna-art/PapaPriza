<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the administrator login view.
     *
     * If the user is already authenticated and is the administrator,
     * redirect directly to the admin orders dashboard.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && strtolower((string) Auth::user()->email) === strtolower((string) config('app.admin_email'))) {
            return redirect()->route('admin.orders');
        }

        return view('auth.login');
    }

    /**
     * Authenticate the administrator credentials.
     *
     * Validates input, verifies that the user matches the configured ADMIN_EMAIL,
     * records an activity log entry, and regenerates the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Only allow the designated admin account.
            if (strtolower((string) Auth::user()->email) !== strtolower((string) config('app.admin_email'))) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'These credentials are not authorized for admin access.',
                ]);
            }

            $admin = Auth::user();
            \App\Models\ActivityLog::record('login', 'Auth', "Admin {$admin->name} ({$admin->email}) logged in.", $admin);

            return redirect()->route('admin.orders');
        }

        return back()->withErrors([
            'email' => 'The email or password is incorrect.',
        ])->onlyInput('email');
    }

    /**
     * Log the administrator out of the application and invalidate the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $admin = Auth::user();
        if ($admin) {
            \App\Models\ActivityLog::record('logout', 'Auth', "Admin {$admin->name} ({$admin->email}) logged out.", $admin);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}