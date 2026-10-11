<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{

// Fields that can be saved
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'description',
        'ip_address',
    ];

      // Connect the activity log to its user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Record an activity log entry.
    public static function record(string $action, string $module, string $description, ?User $user = null): static
    {
          // Get the current user
        $currentUser = $user ?? Auth::user();

        // Get the user's details and IP address
        $userId = $currentUser?->id;
        $userName = $currentUser?->name ?? 'Guest/System';
        $ip = Request::ip();

        // Save the activity details
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
