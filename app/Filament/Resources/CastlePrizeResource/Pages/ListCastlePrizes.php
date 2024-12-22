<?php

namespace App\Filament\Resources\CastlePrizeResource\Pages;

use App\Filament\Resources\CastlePrizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCastlePrizes extends ListRecords
{
    protected static string $resource = CastlePrizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
