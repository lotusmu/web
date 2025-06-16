<?php

namespace App\Filament\Resources\PartnerResource\Widgets;

use App\Models\Partner\Partner;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PartnerPerformanceWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Top Performing Partners This Week';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Partner::query()
                    ->where('status', 'active')
                    ->with(['user'])
                    ->whereHas('streamSessions', function (Builder $query) {
                        $query->whereBetween('started_at', [
                            now()->startOfWeek(),
                            now()->endOfWeek(),
                        ]);
                    })
                    ->withCount(['streamSessions as live_sessions_count' => function (Builder $query) {
                        $query->whereNull('ended_at');
                    }])
                    ->orderByDesc('id')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Partner')
                    ->weight('bold'),

                Tables\Columns\IconColumn::make('live_sessions_count')
                    ->label('Live')
                    ->boolean()
                    ->getStateUsing(fn (Partner $record): bool => $record->live_sessions_count > 0)
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('sessions_this_week')
                    ->label('Sessions This Week')
                    ->getStateUsing(function (Partner $record): string {
                        $count = $record->streamSessions()
                            ->whereBetween('started_at', [
                                now()->startOfWeek(),
                                now()->endOfWeek(),
                            ])
                            ->count();

                        return (string) $count;
                    }),

                Tables\Columns\TextColumn::make('hours_this_week')
                    ->label('Hours This Week')
                    ->getStateUsing(function (Partner $record): string {
                        $sessions = $record->streamSessions()
                            ->whereBetween('started_at', [
                                now()->startOfWeek(),
                                now()->endOfWeek(),
                            ])
                            ->get();
                        $hours = $sessions->sum(fn ($s) => $s->getDurationInHours());

                        return number_format($hours, 1).'h';
                    }),

                Tables\Columns\TextColumn::make('total_viewers_week')
                    ->label('Total Viewers')
                    ->getStateUsing(function (Partner $record): string {
                        $viewers = $record->streamSessions()
                            ->whereBetween('started_at', [
                                now()->startOfWeek(),
                                now()->endOfWeek(),
                            ])
                            ->sum('average_viewers');

                        return number_format($viewers);
                    }),

                Tables\Columns\TextColumn::make('level')
                    ->badge(),

                Tables\Columns\TextColumn::make('promo_code')
                    ->label('Promo Code')
                    ->copyable()
                    ->copyableState(fn (Partner $record): string => $record->promo_code)
                    ->size('sm'),
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\Action::make('view_analytics')
                    ->label('Analytics')
                    ->icon('heroicon-o-chart-bar')
                    ->url(fn (Partner $record): string => "/admin/stream-analytics?tableFilters[partner][value]={$record->id}"
                    ),
            ]);
    }
}
