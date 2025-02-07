<?php

namespace App\Filament\Resources\WeeklyRankingConfigurationResource\Pages;

use App\Filament\Resources\WeeklyRankingConfigurationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWeeklyRankingConfiguration extends EditRecord
{
    protected static string $resource = WeeklyRankingConfigurationResource::class;

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
