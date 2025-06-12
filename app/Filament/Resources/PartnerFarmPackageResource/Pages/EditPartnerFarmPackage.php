<?php

namespace App\Filament\Resources\PartnerFarmPackageResource\Pages;

use App\Filament\Resources\PartnerFarmPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnerFarmPackage extends EditRecord
{
    protected static string $resource = PartnerFarmPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
