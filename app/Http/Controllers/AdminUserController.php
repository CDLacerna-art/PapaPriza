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
    /**
     * Display a listing of registered users with search and order counts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
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

        $users = $usersQuery->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage and record an activity log entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::record(
            'created',
            'Users',
            "Admin created user account {$user->name} ({$user->email})."
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$user->name} created successfully!");
    }

    /**
     * Show the form for editing an existing user.
     *
     * @param  int|string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id): View
    {
        $user = User::withCount('orders')->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage and record an activity log entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

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

    /**
     * Remove the specified user from storage and record an activity log entry.
     *
     * Safety check: Prevents the currently logged-in administrator from deleting their own account.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Safety: Admin cannot delete their own account
        if ($user->id === Auth::id() || strtolower((string) $user->email) === strtolower((string) config('app.admin_email'))) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Safety Violation: You cannot delete the active administrator account.');
        }

        $userName = $user->name;
        $userEmail = $user->email;

        // Record log before deleting
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
