<?php

namespace App\Filament\Resources\SupplyResource\Pages;

use App\Enums\Content\Catalog\SupplyCategory;
use App\Filament\Resources\SupplyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListSupplies extends ListRecords
{
    protected static string $resource = SupplyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Supplies'),
            ...collect(SupplyCategory::cases())->mapWithKeys(fn (SupplyCategory $category) => [
                $category->value => Tab::make($category->getLabel())
                    ->modifyQueryUsing(fn ($query) => $query->where('category', $category->value)),
            ]),
        ];
    }
}
