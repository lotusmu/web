<?php

namespace App\Filament\Resources\BuffResource\Pages;

use App\Filament\Resources\BuffResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBuffs extends ListRecords
{
    protected static string $resource = BuffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
