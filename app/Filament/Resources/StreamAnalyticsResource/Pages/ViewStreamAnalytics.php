<?php

namespace App\Filament\Resources\StreamAnalyticsResource\Pages;

use App\Filament\Resources\StreamAnalyticsResource;
use Filament\Actions\Action;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewStreamAnalytics extends ViewRecord
{
    protected static string $resource = StreamAnalyticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_streams')
                ->label('View Stream Sessions')
                ->icon('heroicon-o-video-camera')
                ->color('info')
                ->url(fn () => "/admin/stream-sessions?tableFilters[partner][value]={$this->record->partner_id}"),

            Action::make('view_partner')
                ->label('View Partner Profile')
                ->icon('heroicon-o-user')
                ->color('primary')
                ->url(fn () => "/admin/partners/{$this->record->partner_id}"),

            Action::make('partner_analytics')
                ->label('All Partner Analytics')
                ->icon('heroicon-o-chart-bar')
                ->color('warning')
                ->url(fn () => "/admin/stream-analytics?tableFilters[partner][value]={$this->record->partner_id}"),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Overview')
                    ->description('Basic information about this analytics record')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('partner.user.name')
                                    ->label('Partner')
                                    ->icon('heroicon-o-user')
                                    ->weight('bold')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('provider')
                                    ->badge()
                                    ->icon('heroicon-o-video-camera'),

                                Infolists\Components\TextEntry::make('date')
                                    ->date('l, F j, Y')
                                    ->icon('heroicon-o-calendar-days'),
                            ]),
                    ]),

                Infolists\Components\Grid::make(2)
                    ->schema([
                        Infolists\Components\Section::make('Streaming Performance')
                            ->description('Quantitative metrics about streaming activity and schedule adherence')
                            ->icon('heroicon-o-chart-bar')
                            ->columns(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_hours_streamed')
                                    ->label('Total Hours Streamed')
                                    ->formatStateUsing(fn ($state) => number_format($state, 1).' hours')
                                    ->icon('heroicon-o-clock')
                                    ->helperText('Time spent streaming on this date'),

                                Infolists\Components\TextEntry::make('stream_count')
                                    ->label('Number of Streams')
                                    ->icon('heroicon-o-play')
                                    ->helperText('Individual streaming sessions'),

                                Infolists\Components\TextEntry::make('average_stream_duration')
                                    ->label('Average Stream Duration')
                                    ->formatStateUsing(fn ($state) => number_format($state, 1).' hours')
                                    ->icon('heroicon-o-clock')
                                    ->helperText('Average length per stream session'),

                                Infolists\Components\TextEntry::make('scheduled_vs_actual_hours')
                                    ->label('Schedule Adherence')
                                    ->formatStateUsing(function ($state) {
                                        $percentage = $state * 100;

                                        return number_format($percentage, 1).'%';
                                    })
                                    ->color(fn ($state) => match (true) {
                                        $state >= 0.9 => 'success',
                                        $state >= 0.7 => 'warning',
                                        default => 'danger'
                                    })
                                    ->icon('heroicon-o-calendar-days')
                                    ->helperText('How well the partner stuck to expected streaming hours'),
                            ]),

                        Infolists\Components\Section::make('Audience Metrics')
                            ->description('Viewer engagement, growth trends, and audience interaction data')
                            ->icon('heroicon-o-users')
                            ->columns(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_viewers')
                                    ->label('Total Viewers')
                                    ->formatStateUsing(fn ($state) => number_format($state))
                                    ->icon('heroicon-o-eye')
                                    ->helperText('Cumulative viewer count across all streams'),

                                Infolists\Components\TextEntry::make('viewer_growth_rate')
                                    ->label('Viewer Growth Rate')
                                    ->formatStateUsing(function ($state) {
                                        $prefix = $state >= 0 ? '+' : '';

                                        return $prefix.number_format($state, 1).'%';
                                    })
                                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                                    ->icon(fn ($state) => $state >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                                    ->helperText('Growth compared to previous week'),

                                Infolists\Components\TextEntry::make('growth_trend')
                                    ->label('Growth Trend')
                                    ->getStateUsing(fn ($record) => $record->getGrowthTrend())
                                    ->badge()
                                    ->color(fn ($state) => match ($state) {
                                        'High Growth' => 'success',
                                        'Growing' => 'info',
                                        'Stable' => 'warning',
                                        'Declining' => 'danger',
                                        default => 'gray',
                                    })
                                    ->icon('heroicon-o-presentation-chart-line')
                                    ->helperText('Overall growth classification'),

                                Infolists\Components\TextEntry::make('chat_activity_score')
                                    ->label('Chat Activity Score')
                                    ->formatStateUsing(fn ($state) => number_format($state, 1).'/100')
                                    ->color(fn ($state) => match (true) {
                                        $state >= 80 => 'success',
                                        $state >= 60 => 'warning',
                                        default => 'danger'
                                    })
                                    ->icon('heroicon-o-chat-bubble-left-right')
                                    ->helperText('Engagement level based on viewer interaction'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Consistency & Habits')
                    ->icon('heroicon-o-chart-pie')
                    ->description('Long-term streaming patterns, reliability, and commitment indicators')
                    ->columns(3)
                    ->schema([
                        Infolists\Components\TextEntry::make('days_streamed_this_week')
                            ->label('Days Streamed This Week')
                            ->formatStateUsing(fn ($state) => $state.'/7 days')
                            ->color(fn ($state) => match (true) {
                                $state >= 6 => 'success',
                                $state >= 4 => 'warning',
                                default => 'danger'
                            })
                            ->icon('heroicon-o-calendar-days')
                            ->helperText('Weekly streaming frequency'),

                        Infolists\Components\TextEntry::make('longest_streak_days')
                            ->label('Longest Streak')
                            ->formatStateUsing(fn ($state) => $state.' days')
                            ->icon('heroicon-o-fire')
                            ->helperText('Consecutive days streaming (last 30 days)'),

                        Infolists\Components\TextEntry::make('consistency_score')
                            ->label('Consistency Rating')
                            ->getStateUsing(fn ($record) => $record->getConsistencyScore())
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'Excellent' => 'success',
                                'Good' => 'info',
                                'Fair' => 'warning',
                                'Poor' => 'danger',
                                default => 'gray',
                            })
                            ->icon('heroicon-o-star')
                            ->helperText('Overall streaming reliability'),
                    ]),

            ]);
    }
}
