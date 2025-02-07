<?php

namespace App\Filament\Resources\WeeklyRankingConfigurationResource\Pages;

use App\Filament\Resources\WeeklyRankingConfigurationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWeeklyRankingConfiguration extends CreateRecord
{
    protected static string $resource = WeeklyRankingConfigurationResource::class;

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
