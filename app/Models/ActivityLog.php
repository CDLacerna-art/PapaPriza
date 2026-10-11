<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'description',
        'ip_address',
    ];

    /**
     * Relationship to the user who performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an activity log entry.
     *
     * Automatically captures the authenticated user (or specified name) and request IP address.
     *
     * @param string $action The action performed (e.g. created, updated, cancelled, deleted, login, logout)
     * @param string $module The module affected (e.g. Orders, Auth)
     * @param string $description A human-readable description of the activity
     * @param User|null $user Optional user override (useful during login/logout)
     * @return static
     */
    public static function record(string $action, string $module, string $description, ?User $user = null): static
    {
        $currentUser = $user ?? Auth::user();

        $userId = $currentUser?->id;
        $userName = $currentUser?->name ?? 'Guest/System';
        $ip = Request::ip();

        return static::create([
            'user_id' => $userId,
            'user_name' => $userName,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => $ip,
        ]);
    }
}
