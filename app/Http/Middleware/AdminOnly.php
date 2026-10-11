<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * Only allow access if the authenticated user's email matches
     * config('app.admin_email') (compared case-insensitively).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        $adminEmail = strtolower((string) config('app.admin_email'));
        $userEmail = strtolower((string) $user->email);

        if ($userEmail !== $adminEmail) {
            abort(403, 'Unauthorized. Admin access only.');
        }

        return $next($request);
    }
}
