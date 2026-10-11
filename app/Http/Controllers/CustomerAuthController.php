<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerAuthController extends Controller
{
    /**
     * Show the customer account registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister(): View
    {
        return view('customer.register');
    }

    /**
     * Handle customer registration, hash password, prevent admin email hijacking,
     * log in the new customer, and record an activity log entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (strtolower((string) $value) === strtolower((string) config('app.admin_email'))) {
                        $fail('This email address is reserved for system administration and cannot be registered as a customer.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        \App\Models\ActivityLog::record('register', 'Auth', "Customer {$user->name} ({$user->email}) registered an account.", $user);

        return redirect()->route('customer.orders');
    }

    /**
     * Show the customer login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin(): View
    {
        return view('customer.login');
    }

    /**
     * Authenticate customer login credentials.
     *
     * Ensures admin account credentials cannot log in via the customer login portal.
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

        if (
            Auth::attempt($credentials) &&
            strtolower((string) Auth::user()->email) !== strtolower((string) config('app.admin_email'))
        ) {
            $user = Auth::user();
            $request->session()->regenerate();

            \App\Models\ActivityLog::record('login', 'Auth', "Customer {$user->name} ({$user->email}) logged in.", $user);

            return redirect()->route('customer.orders');
        }

        if (Auth::check()) {
            Auth::logout();
        }

        return back()->withErrors([
            'email' => 'The customer email or password is incorrect.',
        ])->onlyInput('email');
    }

    /**
     * Log the customer out of the application and invalidate the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            \App\Models\ActivityLog::record('logout', 'Auth', "Customer {$user->name} ({$user->email}) logged out.", $user);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}