<?php

namespace App\Filament\Resources\PartnerReviewResource\Pages;

use App\Filament\Resources\PartnerReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPartnerReviews extends ListRecords
{
    protected static string $resource = PartnerReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
