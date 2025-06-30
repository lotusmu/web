<?php

namespace App\Actions\Partner;

use App\Enums\Game\AccountLevel;
use App\Enums\Partner\PartnerStatus;
use App\Enums\Utility\ActivityType;
use App\Models\Partner\Partner;
use Exception;
use Log;

class ExtendPartnerVip
{
    public function handle(): array
    {
        $activePartners = Partner::where('status', PartnerStatus::ACTIVE)
            ->with('user.member')
            ->get();

        $results = [
            'processed' => 0,
            'extended' => 0,
            'upgraded' => 0,
            'errors' => 0,
        ];

        foreach ($activePartners as $partner) {
            try {
                $results['processed']++;

                // Check VIP status before processing
                $hadExistingVip = $partner->user->hasValidVipSubscription();

                if ($this->processPartnerVip($partner)) {
                    if ($hadExistingVip) {
                        $results['extended']++;
                    } else {
                        $results['upgraded']++;
                    }
                }
            } catch (Exception $e) {
                $results['errors']++;
                Log::error('Failed to extend VIP for partner', [
                    'partner_id' => $partner->id,
                    'user_id' => $partner->user_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    public function handleSinglePartner(Partner $partner): bool
    {
        try {
            return $this->processPartnerVip($partner);
        } catch (Exception $e) {
            Log::error('Failed to extend VIP for single partner', [
                'partner_id' => $partner->id,
                'user_id' => $partner->user_id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function processPartnerVip(Partner $partner): bool
    {
        $user = $partner->user;
        $member = $user->member;

        if (! $member) {
            return false;
        }

        // Check VIP status BEFORE making changes
        $hadExistingVip = $user->hasValidVipSubscription();

        // Always set to Gold level
        $member->AccountLevel = AccountLevel::Gold;

        // Extend VIP appropriately
        if ($hadExistingVip) {
            // Extend existing VIP by 1 day from current expiry
            $member->AccountExpireDate = $member->AccountExpireDate->addDay();
        } else {
            // Give VIP until 06:01 tomorrow for new VIP users
            $member->AccountExpireDate = now()->addDay()->setTime(6, 1);
        }

        $member->save();

        // Log the activity
        $this->logActivity($partner, $hadExistingVip);

        return true;
    }

    private function hadExistingVip(Partner $partner): bool
    {
        // This method is now only used in the results counting
        return $partner->user->hasValidVipSubscription();
    }

    private function logActivity(Partner $partner, bool $wasExtension): void
    {
        $action = $wasExtension ? 'extended' : 'granted';

        activity('partner_vip')
            ->performedOn($partner->user)
            ->withProperties([
                'activity_type' => ActivityType::DEFAULT,
                'partner_id' => $partner->id,
                'partner_level' => $partner->level->getLabel(),
                'promo_code' => $partner->promo_code,
                'action_type' => $action,
                'vip_level' => AccountLevel::Gold->getLabel(),
                'duration_days' => 1,
            ])
            ->log("Partner VIP {$action}: ".($wasExtension ? 'Extended existing VIP' : 'Granted VIP').' for active partner status.');
    }
}
