<?php

namespace App\Filament\Resources\StreamSessionResource\Pages;

use App\Actions\Stream\GenerateStreamAnalytics;
use App\Actions\Stream\SyncStreamsAction;
use App\Filament\Resources\StreamSessionResource;
use App\Models\Stream\StreamSession;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListStreamSessions extends ListRecords
{
    protected static string $resource = StreamSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync_streams')
                ->label('Sync Streams')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->action(function () {
                    $syncAction = new SyncStreamsAction;
                    $results = $syncAction->handle();

                    if ($results['success']) {
                        Notification::make()
                            ->title('Streams synced successfully!')
                            ->body($syncAction->getFormattedResults($results))
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Sync failed')
                            ->body($syncAction->getFormattedResults($results))
                            ->danger()
                            ->send();
                    }

                    // Refresh the table
                    $this->resetTable();
                })
                ->requiresConfirmation()
                ->modalDescription('This will check all active partners for live streams and update the database.'),

            Actions\Action::make('generate_analytics')
                ->label('Generate Analytics')
                ->icon('heroicon-o-chart-bar')
                ->color('warning')
                ->action(function () {
                    $analyticsAction = new GenerateStreamAnalytics;
                    $results = $analyticsAction->handle();

                    Notification::make()
                        ->title('Analytics generated!')
                        ->body("Processed {$results['partners_processed']} partners. Created {$results['analytics_created']}, updated {$results['analytics_updated']} analytics records.")
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalDescription('This will generate today\'s analytics for all partners with stream data.'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Streams'),

            'live' => Tab::make('Live Now')
                ->modifyQueryUsing(fn (Builder $query) => $query->live())
                ->badge(StreamSession::live()->count())
                ->badgeColor('success'),

            'today' => Tab::make('Today')
                ->modifyQueryUsing(fn (Builder $query) => $query->today())
                ->badge(StreamSession::today()->count()),

            'this_week' => Tab::make('This Week')
                ->modifyQueryUsing(fn (Builder $query) => $query->thisWeek())
                ->badge(StreamSession::thisWeek()->count()),
        ];
    }
}
