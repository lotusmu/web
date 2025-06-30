<?php

namespace App\Actions\Partner;

use App\Actions\Member\UpdateItemBank;
use App\Enums\Partner\PartnerStatus;
use App\Enums\Utility\ActivityType;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerFarmPackage;
use Exception;
use Log;

class DistributeFarmRewards
{
    public function handle(): array
    {
        $activePartners = Partner::where('status', PartnerStatus::ACTIVE)
            ->with('user.member')
            ->get();

        $results = [
            'processed' => 0,
            'distributed' => 0,
            'errors' => 0,
        ];

        $currentServer = session('game_db_connection', 'default');

        foreach ($activePartners as $partner) {
            try {
                $results['processed']++;

                if ($this->distributeFarmToPartner($partner, $currentServer)) {
                    $results['distributed']++;
                }
            } catch (Exception $e) {
                $results['errors']++;
                Log::error('Failed to distribute farm rewards to partner', [
                    'partner_id' => $partner->id,
                    'user_id' => $partner->user_id,
                    'server' => $currentServer,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    private function distributeFarmToPartner(Partner $partner, string $server): bool
    {
        // Get farm package for this partner's level
        $farmPackage = PartnerFarmPackage::active()
            ->forLevel($partner->level)
            ->first();

        if (! $farmPackage || ! $partner->user->member) {
            return false;
        }

        // Distribute items using UpdateItemBank
        $itemBank = new UpdateItemBank;
        $itemBank->forAccount($partner->user->member->memb___id);

        foreach ($farmPackage->items as $item) {
            $itemBank->addItem(
                (int) $item['item_index'],
                (int) $item['quantity'],
                (int) ($item['item_level'] ?? 0)
            );
        }

        $itemBank->asIncrement()->execute();

        // Log the distribution
        activity('partner_farm')
            ->performedOn($partner->user)
            ->withProperties([
                'activity_type' => ActivityType::DEFAULT,
                'partner_id' => $partner->id,
                'partner_level' => $partner->level->getLabel(),
                'farm_package' => $farmPackage->name,
                'server' => $server,
            ])
            ->log("Weekly farm rewards received: {$farmPackage->name}");

        return true;
    }
}
