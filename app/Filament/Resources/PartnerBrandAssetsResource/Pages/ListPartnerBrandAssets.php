<?php

namespace App\Filament\Resources\PartnerBrandAssetsResource\Pages;

use App\Filament\Resources\PartnerBrandAssetsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnerBrandAssets extends ListRecords
{
    protected static string $resource = PartnerBrandAssetsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
