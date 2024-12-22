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
        $startDate = Carbon::parse($data['period_starts_at'])
            ->startOfWeek();

        $data['period_starts_at'] = $startDate;

        $data['period_ends_at'] = $startDate
            ->copy()
            ->addWeeks((int) $data['distribution_weeks'])
            ->previous('Sunday')
            ->setTime(23, 59, 59);

        $data['remaining_prize_pool'] = $data['total_prize_pool'];

        return $data;
    }
}
