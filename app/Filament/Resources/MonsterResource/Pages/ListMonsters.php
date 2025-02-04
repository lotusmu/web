<?php

namespace App\Filament\Resources\MonsterResource\Pages;

use App\Filament\Resources\MonsterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListMonsters extends ListRecords
{
    protected static string $resource = MonsterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'rewarding' => Tab::make('Rewarding Monsters')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('PointsPerKill', '>', 0)
                        ->orderBy('PointsPerKill', 'desc');
                }),
            'non_rewarding' => Tab::make('Non-Rewarding Monsters')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('PointsPerKill', 0);
                }),
            'all' => Tab::make('All Monsters'),
        ];
    }
}
