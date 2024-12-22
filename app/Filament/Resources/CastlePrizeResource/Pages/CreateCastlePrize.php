<?php

namespace App\Filament\Resources\CastlePrizeResource\Pages;

use App\Filament\Resources\CastlePrizeResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateCastlePrize extends CreateRecord
{
    protected static string $resource = CastlePrizeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['remaining_prize_pool'] = $data['total_prize_pool'];
        $data['period_ends_at'] = Carbon::parse($data['period_starts_at'])
            ->addWeeks((int) $data['distribution_weeks']);

        return $data;
    }
}
