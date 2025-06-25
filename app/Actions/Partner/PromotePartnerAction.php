<?php

namespace App\Actions\Partner;

use App\Models\Partner\Partner;
use Exception;

class PromotePartnerAction
{
    public function handle(Partner $partner): Partner
    {
        $nextLevel = $partner->level->getNextLevel();

        if (! $nextLevel) {
            throw new Exception('Partner is already at maximum level.');
        }

        $partner->update([
            'level' => $nextLevel,
            'token_percentage' => $nextLevel->getTokenPercentage(),
        ]);

        return $partner->fresh();
    }
}
