<?php

namespace App\Filament\Resources\WeeklyRankingConfigurationResource\Pages;

use App\Filament\Resources\WeeklyRankingConfigurationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeeklyRankingConfigurations extends ListRecords
{
    protected static string $resource = WeeklyRankingConfigurationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
