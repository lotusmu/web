<?php

namespace App\Actions\Partner;

use App\Enums\Partner\PartnerStatus;
use App\Models\Partner\Partner;
use App\Models\Partner\PartnerReview;

class ReviewPartner
{
    public function approve(Partner $partner, ?string $notes = null): PartnerReview
    {
        // Create review record
        $review = PartnerReview::create([
            'partner_id' => $partner->id,
            'week_number' => now()->week,
            'year' => now()->year,
            'decision' => 'approved',
            'notes' => $notes,
        ]);

        // Keep partner active
        $partner->update(['status' => PartnerStatus::ACTIVE]);

        return $review;
    }

    public function reject(Partner $partner, ?string $notes = null): PartnerReview
    {
        // Create review record
        $review = PartnerReview::create([
            'partner_id' => $partner->id,
            'week_number' => now()->week,
            'year' => now()->year,
            'decision' => 'rejected',
            'notes' => $notes,
        ]);

        // Make partner suspended
        $partner->update(['status' => PartnerStatus::SUSPENDED]);

        return $review;
    }
}
