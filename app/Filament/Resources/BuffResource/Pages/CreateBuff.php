<?php

namespace App\Filament\Resources\BuffResource\Pages;

use App\Filament\Resources\BuffResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBuff extends CreateRecord
{
    protected static string $resource = BuffResource::class;

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
