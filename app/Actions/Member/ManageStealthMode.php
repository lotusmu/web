<?php

namespace App\Actions\Member;

use App\Enums\Utility\ActivityType;
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
        if (! $this->validate($user)) {
            return false;
        }

        if ($action === 'enable') {
            return $this->enable($user);
        }

        return $this->extend($user);
    }

    private function validate(User $user, string $action = 'enable'): bool
    {
        if ($user->isOnline()) {
            return false;
        }

        if ($action === 'enable' && $user->hasActiveStealthMode()) {
            Flux::toast(
                text: __('Stealth Mode is already active.'),
                heading: __('Already Active'),
                variant: 'warning'
            );

            return false;
        }

        if ($action === 'extend' && ! $user->hasActiveStealthMode()) {
            Flux::toast(
                text: __('Stealth Mode is not active.'),
                heading: __('Not Active'),
                variant: 'warning'
            );

            return false;
        }

        if ($user->member->tokens < self::STEALTH_MODE_COST) {
            Flux::toast(
                text: __('Insufficient tokens. You need :amount tokens.', [
                    'amount' => self::STEALTH_MODE_COST,
                ]),
                heading: __('Insufficient Tokens'),
                variant: 'warning'
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

        $this->deductTokens($user);
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

        $this->deductTokens($user);
        $this->recordActivity($user, 'extended');

        Flux::toast(
            text: __('Stealth Mode has been extended successfully.'),
            heading: __('Success'),
            variant: 'success'
        );

        return true;
    }

    private function deductTokens(User $user): void
    {
        $user->member->tokens -= self::STEALTH_MODE_COST;
        $user->member->save();
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
                'expires_at' => $user->stealth->expires_at,
                ...IdentityProperties::capture(),
            ])
            ->log('Stealth Mode :properties.action for :subject.name');
    }
}
