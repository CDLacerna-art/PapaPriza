<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    // Display users and search by name or email
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $usersQuery = User::withCount('orders')->latest();

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
    // Show 10 users per page
        $users = $usersQuery->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    // Show the form to add a user
    public function create(): View
    {
        return view('admin.users.create');
    }

    // Save a new user account
    public function store(Request $request): RedirectResponse
    {
        // Check if the input is valid
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (strtolower((string) $value) === strtolower((string) config('app.admin_email'))) {
                        $fail('The admin email address cannot be assigned to another user account.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

    // Create the user and encrypt the password
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Record the activity
        ActivityLog::record(
            'created',
            'Users',
            "Admin created user account {$user->name} ({$user->email})."
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$user->name} created successfully!");
    }

    // Show the form to edit a user
    public function edit($id): View
    {
        $user = User::withCount('orders')->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    // Update user information
    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

    // Check if the input is valid
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
                function ($attribute, $value, $fail) use ($user) {
                    $adminEmail = strtolower((string) config('app.admin_email'));
                    if (strtolower((string) $value) === $adminEmail && strtolower((string) $user->email) !== $adminEmail) {
                        $fail('The admin email address cannot be assigned to another user account.');
                    }
                },
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

    // Change password only if a new one is provided
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        ActivityLog::record(
            'updated',
            'Users',
            "Admin updated user account {$user->name} ({$user->email})."
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$user->name} updated successfully!");
    }

    // Delete a user account
    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Prevent the admin from deleting their own account
        if ($user->id === Auth::id() || strtolower((string) $user->email) === strtolower((string) config('app.admin_email'))) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Safety Violation: You cannot delete the active administrator account.');
        }

        $userName = $user->name;
        $userEmail = $user->email;

        // Record the activity before deleting the user
        ActivityLog::record(
            'deleted',
            'Users',
            "Admin deleted user account {$userName} ({$userEmail})."
        );

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$userName} has been deleted successfully.");
    }
}
