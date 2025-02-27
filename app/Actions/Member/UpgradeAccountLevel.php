<?php

namespace App\Actions\Member;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\User\User;
use App\Models\Utility\GameServer;
use App\Models\Utility\VipPackage;
use App\Support\ActivityLog\IdentityProperties;
use Exception;
use Flux;
use Illuminate\Support\Facades\Log;

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

        $this->backfillDailyRewardsAcrossServers($user->member->memb___id);

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
            ->log('Upgraded account level to :properties.level for :properties.duration days.');
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

    private function backfillDailyRewardsAcrossServers(string $accountId): void
    {
        $currentConnection = session('game_db_connection');

        $activeServers = GameServer::where('is_active', true)->get();

        foreach ($activeServers as $server) {
            try {
                session(['game_db_connection' => $server->connection_name]);

                BackfillDailyRewards::dispatch($accountId, $server->name);
            } catch (Exception $e) {
                Log::error('Failed to dispatch daily rewards backfill', [
                    'account_id' => $accountId,
                    'server' => $server->name,
                    'error' => $e->getMessage(),
                ]);
            } finally {
                session()->forget('game_db_connection');
            }
        }

        if ($currentConnection) {
            session(['game_db_connection' => $currentConnection]);
        }
    }
}
