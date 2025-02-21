<?php

namespace App\Actions\User;

use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;

// Assuming this is a support class you have

class UnbanUser
{
    public function handle(User $user): void
    {
        $bannedAt = $user->banned_at;
        $banDuration = $bannedAt ? now()->diffForHumans($bannedAt, true) : 'unknown duration';
        $reason = $user->ban_reason;

        $user->is_banned = false;
        $user->ban_reason = null;
        $user->banned_at = null;
        $user->save();

        activity('ban')
            ->performedOn($user)
            ->withProperties([
                'action' => 'user_unban',
                'model_type' => 'User',
                'subject_name' => $user->name,
                'previous_ban_duration' => $banDuration,
                'previous_ban_reason' => $reason,
                'unbanned_at' => now()->toIso8601String(),
                ...IdentityProperties::capture(),
            ])
            ->log(":causer.name unbanned User {$user->name} after {$banDuration}");
    }
}
