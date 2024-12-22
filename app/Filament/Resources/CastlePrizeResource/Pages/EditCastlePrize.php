<?php

namespace App\Filament\Resources\CastlePrizeResource\Pages;

use App\Filament\Resources\CastlePrizeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCastlePrize extends EditRecord
{
    protected static string $resource = CastlePrizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
