<?php

namespace App\Filament\Resources;

use App\Enums\Utility\RankingLogStatus;
use App\Filament\Resources\WeeklyRankingLogResource\Pages;
use Artisan;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class WeeklyRankingLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationGroup = 'Rankings';

    protected static ?string $navigationLabel = 'Ranking Process Logs';

    protected static ?string $modelLabel = 'Log';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('log_name', 'weekly_rankings')
            ->latest();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('properties.server')
                    ->label('Server')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Message')
                    ->formatStateUsing(function ($state, $record) {
                        if (isset($record->properties['status'])
                            && $record->properties['status'] === RankingLogStatus::FAILED->value
                            && isset($record->properties['error'])
                        ) {
                            return "{$state}: {$record->properties['error']}";
                        }

                        return $state;
                    })
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('properties.status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => RankingLogStatus::from($state)->getLabel())
                    ->badge()
                    ->color(fn ($state) => RankingLogStatus::from($state)->getColor())
                    ->icon(fn ($state) => RankingLogStatus::from($state)->getIcon()),

            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->native(false)
                    ->options(collect(RankingLogStatus::cases())->mapWithKeys(
                        fn ($status) => [$status->value => $status->getLabel()]
                    ))
                    ->attribute('properties->status'),

                Tables\Filters\SelectFilter::make('server')
                    ->native(false)
                    ->options(function () {
                        $servers = Activity::query()
                            ->where('log_name', 'weekly_rankings')
                            ->get()
                            ->map(fn ($activity) => $activity->properties['server'] ?? null)
                            ->filter()
                            ->unique()
                            ->values();

                        return $servers->combine($servers);
                    })
                    ->attribute('properties->server'),
            ])
            ->actions([
                Tables\Actions\Action::make('recover')
                    ->icon('heroicon-m-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => isset($record->properties['status']) &&
                        $record->properties['status'] === RankingLogStatus::FAILED->value &&
                        $record->created_at->gt(now()->subDay())
                    )
                    ->action(fn ($record) => Artisan::call('rankings:recover-weekly', [
                        'server' => $record->properties['server'],
                    ])
                    ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWeeklyRankingLogs::route('/'),
        ];
    }
}
