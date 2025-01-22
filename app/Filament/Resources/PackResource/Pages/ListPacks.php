<?php

namespace App\Filament\Resources\PackResource\Pages;

use App\Enums\Content\Catalog\PackTier;
use App\Filament\Resources\PackResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListPacks extends ListRecords
{
    protected static string $resource = PackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            ...collect(PackTier::cases())->mapWithKeys(fn (PackTier $category) => [
                $category->value => Tab::make($category->getLabel())
                    ->modifyQueryUsing(fn ($query) => $query->where('tier', $category->value)),
            ]),
        ];
    }
}
