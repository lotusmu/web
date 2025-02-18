<?php

namespace App\Actions\Member;

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
        if (! $this->canUpgrade($user)) {
            return false;
        }

        if (! $user->resource(ResourceType::TOKENS)->decrement($package->cost)) {
            return false;
        }

        $user->member->AccountLevel = $package->level;
        $user->member->AccountExpireDate = now()->addDays($package->duration);
        $user->member->save();

        BackfillDailyRewards::dispatch($user->member->memb___id);

        $this->recordActivity($user, $package);

        Flux::toast(
            text: __('Your account has been upgraded to :level VIP for :duration days.', [
                'level' => $package->level->getLabel(),
                'duration' => $package->duration,
            ]),
            heading: __('Account Upgrade Successful'),
            variant: 'success',
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
            ->log('Upgraded account level to :properties.level for :properties.duration days');
    }

    private function canUpgrade(User $user): bool
    {
        if ($user->hasValidVipSubscription()) {

            Flux::toast(
                text: __('You already have an active VIP subscription until :date.', [
                    'date' => $user->member->AccountExpireDate->format('Y-m-d H:i'),
                ]),
                heading: __('Cannot Upgrade'),
                variant: 'danger',
            );

            return false;
        }

        return true;
    }
}
