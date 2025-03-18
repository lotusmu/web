<?php

namespace App\Filament\Widgets;

use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PopularPackagesChart extends ChartWidget
{
    protected static ?string $heading = 'Popular Packages';

    protected static ?string $pollingInterval = '60s';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 4,
    ];

    protected function getData(): array
    {
        // Get package sales data
        $packageData = Order::select('token_package_id', DB::raw('COUNT(*) as count'))
            ->where('status', 'completed')
            ->groupBy('token_package_id')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $package = TokenPackage::find($item->token_package_id);

                return [
                    'package' => $package ? $package->name : 'Unknown',
                    'count' => $item->count,
                ];
            });

        return [
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => $packageData->pluck('count'),
                    'backgroundColor' => [
                        'rgba(224, 17, 95, 0.5)',   // Ruby red
                        'rgba(70, 130, 180, 0.5)',  // Steel blue
                        'rgba(255, 215, 0, 0.5)',   // Gold
                        'rgba(72, 209, 204, 0.5)',  // Turquoise
                        'rgba(205, 127, 50, 0.5)',  // Bronze
                        'rgba(192, 192, 192, 0.5)', // Silver
                        'rgba(15, 82, 186, 0.5)',   // Sapphire blue
                        'rgba(185, 242, 255, 0.5)', // Diamond blue
                    ],
                    'borderColor' => [
                        'rgb(224, 17, 95)',         // Ruby red
                        'rgb(70, 130, 180)',        // Steel blue
                        'rgb(255, 215, 0)',         // Gold
                        'rgb(72, 209, 204)',        // Turquoise
                        'rgb(205, 127, 50)',        // Bronze
                        'rgb(192, 192, 192)',       // Silver
                        'rgb(15, 82, 186)',         // Sapphire blue
                        'rgb(185, 242, 255)',       // Diamond blue
                    ],
                    'borderWidth' => 2,
                    'borderRadius' => 10,
                ],
            ],
            'labels' => $packageData->pluck('package'),
        ];
    }

    public function getDescription(): ?string
    {
        return 'Packages by number of completed orders • Customer preference analysis';
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
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
            'indexAxis' => 'y', // This makes it a horizontal bar chart
        ];
    }
}
