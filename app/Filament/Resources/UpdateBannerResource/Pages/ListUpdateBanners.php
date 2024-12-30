<?php

namespace App\Filament\Resources\UpdateBannerResource\Pages;

use App\Filament\Resources\UpdateBannerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUpdateBanners extends ListRecords
{
    protected static string $resource = UpdateBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
