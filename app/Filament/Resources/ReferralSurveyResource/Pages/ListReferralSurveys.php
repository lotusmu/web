<?php

namespace App\Filament\Resources\ReferralSurveyResource\Pages;

use App\Filament\Resources\ReferralSurveyResource;
use App\Filament\Resources\ReferralSurveyResource\Widgets\ReferralSourcesChart;
use App\Filament\Resources\ReferralSurveyResource\Widgets\SurveyStatsWidget;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListReferralSurveys extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ReferralSurveyResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            SurveyStatsWidget::class,
            ReferralSourcesChart::class,
            ReferralSurveyResource\Widgets\MMOTopSitesChart::class,
        ];
    }
}
