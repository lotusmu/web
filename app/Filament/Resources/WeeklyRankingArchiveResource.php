<?php

namespace App\Filament\Resources;

use App\Enums\Utility\RankingScoreType;
use App\Filament\Resources\WeeklyRankingArchiveResource\Pages;
use App\Models\Game\Ranking\WeeklyRankingArchive;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WeeklyRankingArchiveResource extends Resource
{
    protected static ?string $model = WeeklyRankingArchive::class;

    protected static ?string $navigationGroup = 'Rankings';

    protected static ?string $modelLabel = 'Weekly Ranking Archive';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Empty since this is read-only archive
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('server.name')
                    ->label('Server')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable(),

                TextColumn::make('cycle_start')
                    ->label('Cycle Start')
                    ->date()
                    ->sortable(),

                TextColumn::make('cycle_end')
                    ->label('Cycle End')
                    ->date()
                    ->sortable(),

                TextColumn::make('rank')
                    ->label('Rank')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('character_name')
                    ->label('Character')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('score')
                    ->label('Score')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('rewards_given')
                    ->label('Rewards')
                    ->formatStateUsing(function ($state) {
                        return collect($state)->map(function ($reward) {
                            return view('components.resource-badge', [
                                'value' => $reward['amount'],
                                'resource' => ResourceType::from($reward['type']),
                            ]);
                        })->join(' ');
                    })
                    ->html(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(RankingScoreType::class)
                    ->label('Type'),

                SelectFilter::make('game_server_id')
                    ->relationship('server', 'name')
                    ->label('Server'),
            ])
            ->defaultSort('cycle_end', 'desc')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWeeklyRankingArchives::route('/'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['character_name'];
    }
}
