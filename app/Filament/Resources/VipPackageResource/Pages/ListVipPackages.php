<?php

namespace App\Filament\Resources\VipPackageResource\Pages;

use App\Filament\Resources\VipPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVipPackages extends ListRecords
{
    protected static string $resource = VipPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
