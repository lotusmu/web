<?php

namespace App\Filament\Resources\CharacterResource\Widgets;

use App\Models\Game\Character;
use Filament\Widgets\ChartWidget;

class CharacterResetsDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Character Resets Distribution';

    // Show in Character resource page
    protected static bool $isLazy = false;

    protected static ?string $maxHeight = '150px';

    protected function getData(): array
    {
        // Define reset ranges
        $ranges = [
            '0' => [0, 0],
            '1-2' => [1, 2],
            '3-5' => [3, 5],
            '6-10' => [6, 10],
            '11-20' => [11, 20],
            '21-30' => [21, 30],
            '31-40' => [31, 40],
            '41-50' => [41, 50],
            '51-60' => [51, 60],
            '61-80' => [61, 80],
            '81-100' => [81, 100],
            '101+' => [101, PHP_INT_MAX],
        ];

        // Initialize counts for each range
        $distribution = array_fill_keys(array_keys($ranges), 0);

        // Count characters in each reset range
        foreach ($ranges as $label => [$min, $max]) {
            $distribution[$label] = Character::query()
                ->whereBetween('ResetCount', [$min, $max])
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Character Resets',
                    'data' => array_values($distribution),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)', // Blue
                    'borderColor' => 'rgb(59, 130, 246)',
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
