<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
     // Show the login page
    public function showLogin(): View|RedirectResponse
    {

    // Redirect to dashboard if admin is already logged in
        if (Auth::check() && strtolower((string) Auth::user()->email) === strtolower((string) config('app.admin_email'))) {
            return redirect()->route('admin.orders');
        }

        return view('auth.login');
    }

    // Log in the administrator
    public function login(Request $request): RedirectResponse
    {

     // Check the login details
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

        // Only allow the designated admin account
            if (strtolower((string) Auth::user()->email) !== strtolower((string) config('app.admin_email'))) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'These credentials are not authorized for admin access.',
                ]);
            }

            // Record the admin login in the activity log
            $admin = Auth::user();
            \App\Models\ActivityLog::record('login', 'Auth', "Admin {$admin->name} ({$admin->email}) logged in.", $admin);

            return redirect()->route('admin.orders');
        }

        // Show an error if the login details are incorrect
        return back()->withErrors([
            'email' => 'The email or password is incorrect.',
        ])->onlyInput('email');
    }

    // Log out the administrator
    public function logout(Request $request): RedirectResponse
    {
        $admin = Auth::user();

        // Record the admin logout in the activity log
        if ($admin) {
            \App\Models\ActivityLog::record('logout', 'Auth', "Admin {$admin->name} ({$admin->email}) logged out.", $admin);
        }

        Auth::logout();

        // Clear the session for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}