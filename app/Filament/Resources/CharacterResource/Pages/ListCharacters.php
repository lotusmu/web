<?php

namespace App\Filament\Resources\CharacterResource\Pages;

use App\Filament\Resources\CharacterResource;
use App\Filament\Resources\CharacterResource\Widgets\CharacterQuestDistributionChart;
use App\Filament\Resources\CharacterResource\Widgets\CharacterResetsDistributionChart;
use Filament\Resources\Pages\ListRecords;

class ListCharacters extends ListRecords
{
    protected static string $resource = CharacterResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            CharacterResetsDistributionChart::class,
            CharacterQuestDistributionChart::class,
        ];
    }
}
