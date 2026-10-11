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
    //Show the customer account registration form.
    public function showRegister(): View
    {
        return view('customer.register');
    }

    // register a new customer
    public function register(Request $request): RedirectResponse
    {
        // Check if the input is valid
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                function ($attribute, $value, $fail) {

                 // Prevent customers from using the admin email
                    if (strtolower((string) $value) === strtolower((string) config('app.admin_email'))) {
                        $fail('This email address is reserved for system administration and cannot be registered as a customer.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

         // Create the customer and encrypt the password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

         // Log in the new customer
        Auth::login($user);
        $request->session()->regenerate();

     // Record the registration in the activity log
        \App\Models\ActivityLog::record('register', 'Auth', "Customer {$user->name} ({$user->email}) registered an account.", $user);

        return redirect()->route('customer.orders');
    }

    // Show the customer login form.
    public function showLogin(): View
    {
        return view('customer.login');
    }

     // Log in an existing customer
    public function login(Request $request): RedirectResponse
    {

    // Check the login details
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Allow customer accounts only
        if (
            Auth::attempt($credentials) &&
            strtolower((string) Auth::user()->email) !== strtolower((string) config('app.admin_email'))
        ) {
            $user = Auth::user();
            $request->session()->regenerate();

            \App\Models\ActivityLog::record('login', 'Auth', "Customer {$user->name} ({$user->email}) logged in.", $user);

            return redirect()->route('customer.orders');
        }

          // Log out if the account is not allowed
        if (Auth::check()) {
            Auth::logout();
        }

        // Show an error for incorrect login details
        return back()->withErrors([
            'email' => 'The customer email or password is incorrect.',
        ])->onlyInput('email');
    }

    // Log the customer out of the application and invalidate the session.
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            \App\Models\ActivityLog::record('logout', 'Auth', "Customer {$user->name} ({$user->email}) logged out.", $user);
        }

        Auth::logout();

         // Clear the session for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}