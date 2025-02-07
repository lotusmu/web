<?php

namespace App\Filament\Resources\WeeklyRankingRewardResource\Pages;

use App\Filament\Resources\WeeklyRankingRewardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWeeklyRankingReward extends CreateRecord
{
    protected static string $resource = WeeklyRankingRewardResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
