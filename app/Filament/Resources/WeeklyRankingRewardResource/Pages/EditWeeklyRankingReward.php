<?php

namespace App\Filament\Resources\WeeklyRankingRewardResource\Pages;

use App\Filament\Resources\WeeklyRankingRewardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWeeklyRankingReward extends EditRecord
{
    protected static string $resource = WeeklyRankingRewardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
