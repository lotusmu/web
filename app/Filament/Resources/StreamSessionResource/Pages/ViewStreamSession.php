<?php

namespace App\Filament\Resources\StreamSessionResource\Pages;

use App\Filament\Resources\StreamSessionResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewStreamSession extends ViewRecord
{
    protected static string $resource = StreamSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_partner')
                ->label('View Partner')
                ->icon('heroicon-o-user')
                ->color('primary')
                ->url(fn () => "/admin/partners/{$this->record->partner_id}"),

            Actions\Action::make('partner_analytics')
                ->label('Partner Analytics')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->url(fn () => "/admin/stream-analytics?tableFilters[partner][value]={$this->record->partner_id}"),

            Actions\Action::make('partner_sessions')
                ->label('All Partner Sessions')
                ->icon('heroicon-o-video-camera')
                ->color('warning')
                ->url(fn () => "/admin/stream-sessions?tableFilters[partner][value]={$this->record->partner_id}"),

            Actions\Action::make('view_on_platform')
                ->label('View on Twitch')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('purple')
                ->url(fn () => "https://twitch.tv/{$this->record->channel_name}")
                ->openUrlInNewTab(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Stream Overview')
                    ->description('Basic information about this streaming session')
                    ->schema([
                        Infolists\Components\Grid::make(5)
                            ->schema([
                                Infolists\Components\TextEntry::make('partner.user.name')
                                    ->label('Partner')
                                    ->icon('heroicon-o-user')
                                    ->weight('bold')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('provider')
                                    ->badge()
                                    ->icon('heroicon-o-video-camera'),

                                Infolists\Components\TextEntry::make('channel_name')
                                    ->label('Channel')
                                    ->icon('heroicon-o-tv')
                                    ->copyable(),

                                Infolists\Components\TextEntry::make('external_stream_id')
                                    ->label('Stream ID')
                                    ->icon('heroicon-o-hashtag')
                                    ->copyable(),

                                Infolists\Components\TextEntry::make('live_status')
                                    ->label('Status')
                                    ->getStateUsing(fn ($record) => $record->isLive() ? 'Live' : 'Ended')
                                    ->badge()
                                    ->color(fn ($record) => $record->isLive() ? 'success' : 'gray')
                                    ->icon(fn ($record) => $record->isLive() ? 'heroicon-o-signal' : 'heroicon-o-stop-circle'),
                            ]),

                    ]),

                Infolists\Components\Grid::make(2)
                    ->schema([
                        Infolists\Components\Section::make('Content Details')
                            ->description('Stream title, category, and content information')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                                    ->label('Stream Title')
                                    ->helperText('The title displayed on the streaming platform'),

                                Infolists\Components\TextEntry::make('game_category')
                                    ->label('Game/Category')
                                    ->placeholder('Not specified')
                                    ->helperText('What game or content category was being streamed'),

                                Infolists\Components\Grid::make(3)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('language')
                                            ->label('Language')
                                            ->icon('heroicon-o-language')
                                            ->helperText('Primary language of the stream'),

                                        Infolists\Components\TextEntry::make('stream_tags')
                                            ->label('Tags')
                                            ->listWithLineBreaks()
                                            ->bulleted()
                                            ->icon('heroicon-o-tag')
                                            ->placeholder('No tags')
                                            ->helperText('Content tags applied to the stream'),

                                        Infolists\Components\IconEntry::make('mature_content')
                                            ->label('Mature Content')
                                            ->boolean()
                                            ->trueIcon('heroicon-o-exclamation-triangle')
                                            ->falseIcon('heroicon-o-check-circle')
                                            ->trueColor('warning')
                                            ->falseColor('success')
                                            ->helperText('Whether this stream was marked as mature content'),
                                    ]),
                            ]),

                        Infolists\Components\Section::make('Timing & Duration')
                            ->description('When the stream started, ended, and how long it lasted')
                            ->icon('heroicon-o-clock')
                            ->columns(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('started_at')
                                    ->label('Started At')
                                    ->dateTime('l, F j, Y \a\t g:i A')
                                    ->helperText('When the stream began'),

                                Infolists\Components\TextEntry::make('ended_at')
                                    ->label('Ended At')
                                    ->dateTime('l, F j, Y \a\t g:i A')
                                    ->placeholder('Still live')
                                    ->icon('heroicon-o-stop')
                                    ->helperText('When the stream ended (if applicable)'),

                                Infolists\Components\TextEntry::make('duration')
                                    ->label('Duration')
                                    ->getStateUsing(function ($record): string {
                                        $hours = $record->getDurationInHours();
                                        $totalMinutes = $hours * 60;
                                        $displayHours = floor($totalMinutes / 60);
                                        $displayMinutes = $totalMinutes % 60;

                                        if ($displayHours > 0) {
                                            return $displayHours.'h '.round($displayMinutes).'m';
                                        }

                                        return round($totalMinutes).'m';
                                    })
                                    ->icon('heroicon-o-clock')
                                    ->helperText('Total streaming time'),

                                Infolists\Components\TextEntry::make('streaming_time')
                                    ->label('Time of Day')
                                    ->getStateUsing(function ($record): string {
                                        $hour = $record->hour_of_day;
                                        $dayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$record->day_of_week];

                                        return $dayName.' at '.$hour.':00';
                                    })
                                    ->icon('heroicon-o-calendar-days')
                                    ->helperText('Day of week and hour when stream started'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Viewer Statistics')
                    ->description('Audience metrics and engagement data from this streaming session')
                    ->icon('heroicon-o-users')
                    ->columns(3)
                    ->schema([
                        Infolists\Components\TextEntry::make('peak_viewers')
                            ->label('Peak Viewers')
                            ->formatStateUsing(fn ($state) => number_format($state))
                            ->icon('heroicon-o-arrow-trending-up')
                            ->color('success')
                            ->helperText('Highest concurrent viewer count'),

                        Infolists\Components\TextEntry::make('average_viewers')
                            ->label('Average Viewers')
                            ->formatStateUsing(fn ($state) => number_format($state))
                            ->icon('heroicon-o-eye')
                            ->color('info')
                            ->helperText('Average concurrent viewers throughout the stream'),

                        Infolists\Components\TextEntry::make('viewer_retention')
                            ->label('Viewer Retention')
                            ->getStateUsing(function ($record): string {
                                if ($record->peak_viewers == 0) {
                                    return 'N/A';
                                }
                                $retention = ($record->average_viewers / $record->peak_viewers) * 100;

                                return number_format($retention, 1).'%';
                            })
                            ->color(fn ($record) => match (true) {
                                $record->peak_viewers == 0 => 'gray',
                                ($record->average_viewers / max($record->peak_viewers, 1)) >= 0.7 => 'success',
                                ($record->average_viewers / max($record->peak_viewers, 1)) >= 0.5 => 'warning',
                                default => 'danger'
                            })
                            ->icon('heroicon-o-chart-pie')
                            ->helperText('How well the stream retained viewers (average/peak ratio)'),
                    ]),
            ]);
    }
}
