<?php

namespace App\Filament\Resources\StreamAnalyticsResource\Pages;

use App\Filament\Resources\StreamAnalyticsResource;
use App\Filament\Resources\StreamAnalyticsResource\Widgets\StreamOverviewWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStreamAnalytics extends ListRecords
{
    protected static string $resource = StreamAnalyticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StreamOverviewWidget::class,
        ];
    }
}
