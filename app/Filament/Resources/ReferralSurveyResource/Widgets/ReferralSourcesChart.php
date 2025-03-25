<?php

namespace App\Filament\Resources\ReferralSurveyResource\Widgets;

use App\Enums\Survey\ReferralSource;
use App\Models\User\ReferralSurvey;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ReferralSourcesChart extends ChartWidget
{
    protected static ?string $heading = 'Referral Sources Distribution';

    protected static ?string $maxHeight = '250px';

    protected int|string|array $columnSpan = '50%';

    protected function getData(): array
    {
        $query = ReferralSurvey::query()
            ->where('completed', true)
            ->whereNotNull('referral_source');

        $sources = $query->select(DB::raw('referral_source as source_value'), DB::raw('count(*) as count'))
            ->groupBy('referral_source')
            ->orderBy('count', 'desc')
            ->get();

        $labels = $sources->map(function ($source) {
            return ReferralSource::from($source->source_value)->shortLabel();
        })->toArray();

        $counts = $sources->pluck('count')->toArray();

        $backgroundColors = $sources->map(function ($source) {
            return ReferralSource::from($source->source_value)->getChartBackgroundColor();
        })->toArray();

        $borderColors = $sources->map(function ($source) {
            return ReferralSource::from($source->source_value)->getChartBorderColor();
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Referral Sources',
                    'data' => $counts,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => '2',
                    'borderRadius' => '10',
                ],
            ],
            'labels' => $labels,
        ];
    }

    public function getDescription(): ?string
    {
        $totalCompletedSurveys = ReferralSurvey::where('completed', true)->count();
        $totalSurveysShown = ReferralSurvey::whereNotNull('shown_at')->count();

        $completionRate = $totalSurveysShown > 0 ? round(($totalCompletedSurveys / $totalSurveysShown) * 100, 1) : 0;

        return "{$completionRate}% survey completion rate • Distribution by referral source";
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0, // Only show whole numbers
                    ],
                ],
            ],
        ];
    }
}
