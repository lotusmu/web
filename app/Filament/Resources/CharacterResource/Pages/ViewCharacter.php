<?php

namespace App\Filament\Resources\CharacterResource\Pages;

use App\Filament\Resources\CharacterResource;
use App\Models\Character;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCharacter extends ViewRecord
{
    protected static string $resource = CharacterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->form(Character::getForm()),
        ];
    }
}
