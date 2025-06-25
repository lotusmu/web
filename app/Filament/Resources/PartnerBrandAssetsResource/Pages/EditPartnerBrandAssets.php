<?php

namespace App\Filament\Resources\PartnerBrandAssetsResource\Pages;

use App\Filament\Resources\PartnerBrandAssetsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnerBrandAssets extends EditRecord
{
    protected static string $resource = PartnerBrandAssetsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
