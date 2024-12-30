<?php

namespace App\Filament\Resources\UpdateBannerResource\Pages;

use App\Filament\Resources\UpdateBannerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUpdateBanner extends CreateRecord
{
    protected static string $resource = UpdateBannerResource::class;

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
