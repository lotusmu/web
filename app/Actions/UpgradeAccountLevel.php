<?php

namespace App\Actions;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\User\User;
use App\Models\Utility\VipPackage;
use App\Support\ActivityLog\IdentityProperties;
use Flux;

class UpgradeAccountLevel
{
    public function handle(User $user, VipPackage $package): bool
    {
        if (! $user->resource(ResourceType::TOKENS)->decrement($package->cost)) {
            return false;
        }

        $user->member->AccountLevel = $package->level;
        $user->member->AccountExpireDate = now()->addDays($package->duration);
        $user->member->save();

        $this->recordActivity($user, $package);

        Flux::toast(
            variant: 'success',
            heading: 'Account Upgrade Successful',
            text: "Your account has been upgraded to {$package->level->getLabel()} VIP for {$package->duration} days.",
        );

        return true;
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
            ->log('Upgraded account level');
    }
}
