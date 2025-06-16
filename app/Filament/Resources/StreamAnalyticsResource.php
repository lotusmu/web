<?php

namespace App\Filament\Resources;

use App\Enums\Stream\StreamProvider;
use App\Filament\Resources\StreamAnalyticsResource\Pages;
use App\Filament\Resources\StreamAnalyticsResource\Widgets\StreamOverviewWidget;
use App\Models\Stream\StreamAnalytics;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StreamAnalyticsResource extends Resource
{
    protected static ?string $model = StreamAnalytics::class;

    protected static ?string $navigationGroup = 'Partners';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('partner.user.name')
                    ->label('Partner')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('provider')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->date('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_hours_streamed')
                    ->label('Hours')
                    ->numeric(1)
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('stream_count')
                    ->label('Streams')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('total_viewers')
                    ->label('Total Viewers')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('average_stream_duration')
                    ->label('Avg Duration')
                    ->getStateUsing(fn (StreamAnalytics $record): string => number_format($record->average_stream_duration, 1).'h'
                    )
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('consistency_score')
                    ->label('Consistency')
                    ->getStateUsing(fn (StreamAnalytics $record): string => $record->getConsistencyScore()
                    )
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Excellent' => 'success',
                        'Good' => 'info',
                        'Fair' => 'warning',
                        'Poor' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('viewer_growth_rate')
                    ->label('Growth')
                    ->getStateUsing(fn (StreamAnalytics $record): string => ($record->viewer_growth_rate >= 0 ? '+' : '').
                        number_format($record->viewer_growth_rate, 1).'%'
                    )
                    ->color(fn (StreamAnalytics $record): string => $record->viewer_growth_rate >= 0 ? 'success' : 'danger'
                    )
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('growth_trend')
                    ->label('Trend')
                    ->getStateUsing(fn (StreamAnalytics $record): string => $record->getGrowthTrend()
                    )
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'High Growth' => 'success',
                        'Growing' => 'info',
                        'Stable' => 'warning',
                        'Declining' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('days_streamed_this_week')
                    ->label('Days/Week')
                    ->getStateUsing(fn (StreamAnalytics $record): string => $record->days_streamed_this_week.'/7'
                    )
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('longest_streak_days')
                    ->label('Streak')
                    ->getStateUsing(fn (StreamAnalytics $record): string => $record->longest_streak_days.' days'
                    )
                    ->sortable()
                    ->alignEnd(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('provider')
                    ->options(StreamProvider::class),

                Tables\Filters\Filter::make('this_week')
                    ->query(fn (Builder $query): Builder => $query->thisWeek())
                    ->label('This Week'),

                Tables\Filters\Filter::make('this_month')
                    ->query(fn (Builder $query): Builder => $query->thisMonth())
                    ->label('This Month'),

                Tables\Filters\SelectFilter::make('partner')
                    ->relationship('partner.user', 'name')
                    ->searchable(),

                Tables\Filters\Filter::make('high_performance')
                    ->query(fn (Builder $query): Builder => $query->where('viewer_growth_rate', '>', 10)
                        ->where('total_hours_streamed', '>', 20)
                    )
                    ->label('High Performers'),

                Tables\Filters\Filter::make('needs_attention')
                    ->query(fn (Builder $query): Builder => $query->where('viewer_growth_rate', '<', -10)
                        ->orWhere('total_hours_streamed', '<', 5)
                    )
                    ->label('Needs Attention'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getWidgets(): array
    {
        return [
            StreamOverviewWidget::class,
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Analytics are generated automatically
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStreamAnalytics::route('/'),
            'create' => Pages\CreateStreamAnalytics::route('/create'),
            'view' => Pages\ViewStreamAnalytics::route('/{record}'),
        ];
    }
}
