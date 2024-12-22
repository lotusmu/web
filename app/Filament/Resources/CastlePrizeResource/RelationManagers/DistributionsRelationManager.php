<?php

namespace App\Filament\Resources\CastlePrizeResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DistributionsRelationManager extends RelationManager
{
    protected static string $relationship = 'distributions';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guild_name')
                    ->label('Guild'),
                Tables\Columns\TextColumn::make('total_members')
                    ->label('Members'),
                Tables\Columns\TextColumn::make('distributed_amount')
                    ->label('Distributed')
                    ->numeric(),
                Tables\Columns\TextColumn::make('amount_per_member')
                    ->label('Per Member')
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Date'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
