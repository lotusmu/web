<?php

namespace App\Filament\Resources\UpdateBannerResource\Pages;

use App\Filament\Resources\UpdateBannerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUpdateBanner extends EditRecord
{
    protected static string $resource = UpdateBannerResource::class;

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
