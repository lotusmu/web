<?php

namespace App\Actions\Member;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\User\User;
use App\Support\ActivityLog\IdentityProperties;
use Carbon\Carbon;
use Flux\Flux;

class ManageStealthMode
{
    private const STEALTH_MODE_COST = 60;

    private const STEALTH_MODE_DURATION_DAYS = 7;

    public function handle(User $user, string $action = 'enable'): bool
    {
        if (! $this->validate($user, $action)) {
            return false;
        }

        if (! $user->resource(ResourceType::TOKENS)->decrement(self::STEALTH_MODE_COST)) {
            return false;
        }

        if ($action === 'enable') {
            return $this->enable($user);
        }

        return $this->extend($user);
    }

    private function validate(User $user, string $action = 'enable'): bool
    {
        if ($action === 'enable' && $user->hasActiveStealth()) {
            Flux::toast(
                text: __('Stealth Mode is already enabled on your account.'),
                heading: __('Error'),
                variant: 'danger'
            );

            return false;
        }

        if ($action === 'extend' && ! $user->hasActiveStealth()) {
            Flux::toast(
                text: __('Stealth Mode is not enabled on your account.'),
                heading: __('Error'),
                variant: 'danger'
            );

            return false;
        }

        return true;
    }

    private function enable(User $user): bool
    {
        $user->stealth()->create([
            'expires_at' => Carbon::now()->addDays(self::STEALTH_MODE_DURATION_DAYS),
        ]);

        $user->refresh();

        $this->recordActivity($user, 'enabled');

        Flux::toast(
            text: __('Stealth Mode has been enabled successfully.'),
            heading: __('Success'),
            variant: 'success'
        );

        return true;
    }

    private function extend(User $user): bool
    {
        $user->stealth->update([
            'expires_at' => Carbon::parse($user->stealth->expires_at)
                ->addDays(self::STEALTH_MODE_DURATION_DAYS),
        ]);

        $user->refresh();

        $this->recordActivity($user, 'extended');

        Flux::toast(
            text: __('Stealth Mode has been extended successfully.'),
            heading: __('Success'),
            variant: 'success'
        );

        return true;
    }

    private function recordActivity(User $user, string $action): void
    {
        activity('stealth_mode')
            ->performedOn($user)
            ->withProperties([
                'activity_type' => ActivityType::DECREMENT->value,
                'action' => $action,
                'amount' => self::STEALTH_MODE_COST,
                'duration' => self::STEALTH_MODE_DURATION_DAYS,
                'expires_at' => $user->stealth->expires_at->format('d M Y, H:i'),
                ...IdentityProperties::capture(),
            ])
            ->log('Stealth Mode :properties.action until :properties.expires_at');
    }
}
