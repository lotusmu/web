<?php

namespace App\Filament\Resources\StreamAnalyticsResource\Widgets;

use App\Models\Stream\StreamAnalytics;
use App\Models\Stream\StreamSession;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StreamOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Current live streams
        $liveStreams = StreamSession::live()->count();

        // Today's streams
        $todayStreams = StreamSession::today()->count();
        $todayHours = StreamSession::today()->get()->sum(fn ($s) => $s->getDurationInHours());

        // This week's performance
        $weeklyAnalytics = StreamAnalytics::thisWeek()->get();
        $weeklyTotalViewers = $weeklyAnalytics->sum('total_viewers');
        $weeklyHours = $weeklyAnalytics->sum('total_hours_streamed');

        // Growth calculation
        $lastWeekViewers = StreamAnalytics::whereBetween('date', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->sum('total_viewers');

        $viewerGrowth = $lastWeekViewers > 0
            ? (($weeklyTotalViewers - $lastWeekViewers) / $lastWeekViewers) * 100
            : 0;

        return [
            Stat::make('Live Streams', $liveStreams)
                ->description('Currently broadcasting')
                ->descriptionIcon('heroicon-m-video-camera')
                ->color($liveStreams > 0 ? 'success' : 'gray'),

            Stat::make('Today\'s Streams', $todayStreams)
                ->description(number_format($todayHours, 1).' total hours')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),

            Stat::make('Weekly Viewers', number_format($weeklyTotalViewers))
                ->description(
                    ($viewerGrowth >= 0 ? '+' : '').
                    number_format($viewerGrowth, 1).'% from last week'
                )
                ->descriptionIcon($viewerGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($viewerGrowth >= 0 ? 'success' : 'danger'),

            Stat::make('Weekly Hours', number_format($weeklyHours, 1))
                ->description('Total streaming time')
                ->descriptionIcon('heroicon-m-play')
                ->color('warning'),
        ];
    }
}
