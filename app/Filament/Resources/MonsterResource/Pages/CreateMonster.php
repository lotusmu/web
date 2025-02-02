<?php

namespace App\Filament\Resources\MonsterResource\Pages;

use App\Filament\Resources\MonsterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMonster extends CreateRecord
{
    protected static string $resource = MonsterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
