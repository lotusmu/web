<?php

namespace App\Actions\Member;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\User\User;
use App\Models\Utility\VipPackage;
use App\Support\ActivityLog\IdentityProperties;
use Flux;

class ExtendVipSubscription
{
    public function handle(User $user, VipPackage $package): bool
    {
        if (! $this->canExtend($user)) {
            return false;
        }

        if (! $user->resource(ResourceType::TOKENS)->decrement($package->cost)) {
            return false;
        }

        $this->updateAccountLevel($user, $package);
        $this->extendExpiration($user, $package);
        $user->member->save();

        $this->recordActivity($user, $package);

        Flux::toast(
            text: __('Your VIP subscription has been extended for :duration days.', [
                'duration' => $package->duration,
            ]),
            heading: __('VIP Subscription Extended'),
            variant: 'success',
        );

        return true;
    }

    private function updateAccountLevel(User $user, VipPackage $package): void
    {
        if ($package->level->value > $user->member->AccountLevel->value) {
            $user->member->AccountLevel = $package->level;
        }
    }

    private function extendExpiration(User $user, VipPackage $package): void
    {
        $currentExpiration = $user->member->AccountExpireDate;
        $newExpiration = now()->max($currentExpiration)->addDays($package->duration);
        $user->member->AccountExpireDate = $newExpiration;
    }

    private function recordActivity(User $user, VipPackage $package): void
    {
        activity('vip_activity')
            ->performedOn($user)
            ->withProperties([
                'activity_type' => ActivityType::DECREMENT->value,
                'amount' => $package->cost,
                'level' => $package->level->getLabel(),
                'duration' => $package->duration,
                ...IdentityProperties::capture(),
            ])
            ->log('Extended VIP subscription for :properties.duration days');
    }

    private function canExtend(User $user): bool
    {
        if (! $user->hasValidVipSubscription()) {
            Flux::toast(
                text: __('You need to have an active VIP subscription to extend it.'),
                heading: __('Cannot Extend'),
                variant: 'danger',
            );

            return false;
        }

        return true;
    }
}
