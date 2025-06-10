<?php

namespace App\Actions\Partner;

use App\Enums\Partner\PartnerLevel;
use App\Enums\Partner\PartnerStatus;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerApplication;
use Illuminate\Support\Str;

class CreatePartnerFromApplication
{
    public function handle(PartnerApplication $application): Partner
    {
        $promoCode = $this->generateUniquePromoCode($application->user->name);

        $partner = Partner::create([
            'user_id' => $application->user_id,
            'level' => PartnerLevel::LEVEL_ONE,
            'status' => PartnerStatus::ACTIVE,
            'promo_code' => $promoCode,
            'token_percentage' => 10.00,
            'platforms' => $application->platforms,
            'channels' => $application->channels,
            'approved_at' => now(),
        ]);

        return $partner;
    }

    private function generateUniquePromoCode(string $userName): string
    {
        $baseCode = strtoupper(Str::slug($userName, ''));
        $baseCode = substr($baseCode, 0, 8);

        $promoCode = $baseCode;
        $counter = 1;

        // Ensure uniqueness
        while (Partner::where('promo_code', $promoCode)->exists()) {
            $promoCode = $baseCode.$counter;
            $counter++;
        }

        return $promoCode;
    }
}
