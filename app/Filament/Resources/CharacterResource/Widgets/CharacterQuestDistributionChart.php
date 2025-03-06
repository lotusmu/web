<?php

namespace App\Filament\Resources\CharacterResource\Widgets;

use App\Models\Game\Ranking\Quest;
use Filament\Widgets\ChartWidget;

class CharacterQuestDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Character Quest Distribution';

    // Show in Character resource page
    protected static bool $isLazy = false;

    protected static ?string $maxHeight = '150px';

    protected function getData(): array
    {
        // Define quest ranges
        $ranges = [
            '0' => [0, 0],
            '1-25' => [1, 25],
            '26-50' => [26, 50],
            '51-75' => [51, 75],
            '76-100' => [76, 100],
            '101-150' => [101, 150],
            '151-200' => [151, 200],
            '201-250' => [201, 250],
            '251-300' => [251, 300],
            '301+' => [301, PHP_INT_MAX],
        ];

        // Initialize counts for each range
        $distribution = array_fill_keys(array_keys($ranges), 0);

        // Count characters in each quest range
        foreach ($ranges as $label => [$min, $max]) {
            $distribution[$label] = Quest::query()
                ->whereBetween('Quest', [$min, $max])
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Quest Progress',
                    'data' => array_values($distribution),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)', // Green
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 2,
                    'borderRadius' => 5,
                ],
            ],
            'labels' => array_keys($distribution),
        ];
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
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
