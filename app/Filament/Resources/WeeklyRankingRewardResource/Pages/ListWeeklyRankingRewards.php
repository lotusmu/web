<?php

namespace App\Filament\Resources\WeeklyRankingRewardResource\Pages;

use App\Filament\Resources\WeeklyRankingRewardResource;
use App\Models\Utility\GameServer;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListWeeklyRankingRewards extends ListRecords
{
    protected static string $resource = WeeklyRankingRewardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All Servers'),
        ];

        $servers = GameServer::where('is_active', true)
            ->get()
            ->mapWithKeys(function ($server) {
                return [
                    "server_{$server->id}" => Tab::make($server->name)
                        ->modifyQueryUsing(fn ($query) => $query->whereHas(
                            'configuration',
                            fn ($q) => $q->where('game_server_id', $server->id)
                        )),
                ];
            });

        return $tabs + $servers->toArray();
    }
}
