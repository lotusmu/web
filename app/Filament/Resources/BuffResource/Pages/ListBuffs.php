<?php

namespace App\Filament\Resources\BuffResource\Pages;

use App\Filament\Resources\BuffResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListBuffs extends ListRecords
{
    protected static string $resource = BuffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Buffs'),
            'single' => Tab::make('Single Buffs')
                ->modifyQueryUsing(fn ($query) => $query->where('is_bundle', false)),
            'bundles' => Tab::make('Buff Bundles')
                ->modifyQueryUsing(fn ($query) => $query->where('is_bundle', true)),
        ];
    }
}
