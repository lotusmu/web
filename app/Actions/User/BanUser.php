<?php

namespace App\Actions\User;

use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;

class BanUser
{
    public function handle(User $user, ?string $reason = null): void
    {
        $user->is_banned = true;
        $user->ban_reason = $reason;
        $user->banned_at = now();
        $user->save();

        activity('ban')
            ->performedOn($user)
            ->withProperties([
                'action' => 'user_ban',
                'ban_type' => 'permanent',
                'model_type' => 'User',
                'subject_name' => $user->name,
                'reason' => $reason ?: 'No reason provided',
                'banned_at' => now()->toIso8601String(),
                ...IdentityProperties::capture(),
            ])
            ->log(":causer.name permanently banned User {$user->name}".($reason ? " - Reason: {$reason}" : ''));
    }
}
