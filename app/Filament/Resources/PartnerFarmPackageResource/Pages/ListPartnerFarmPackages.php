<?php

namespace App\Filament\Resources\PartnerFarmPackageResource\Pages;

use App\Filament\Resources\PartnerFarmPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnerFarmPackages extends ListRecords
{
    protected static string $resource = PartnerFarmPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
