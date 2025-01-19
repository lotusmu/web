<?php

namespace App\Filament\Resources\BuffResource\Pages;

use App\Filament\Resources\BuffResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuff extends EditRecord
{
    protected static string $resource = BuffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
