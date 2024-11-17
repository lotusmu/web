<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Symfony\Component\Intl\Countries;

class RevenueByCountryChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '200px';

    protected static ?string $heading = 'Revenue by Country';

    protected function getData(): array
    {
        $revenueByCountry = Order::query()
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(payment_data, '$.customer_details.address.country')) as country")
            ->selectRaw('SUM(amount) as total_revenue')
            ->whereRaw("JSON_EXTRACT(payment_data, '$.customer_details.address.country') IS NOT NULL")
            ->groupBy('country')
            ->pluck('total_revenue', 'country');

        $colors = [
            '#F59E0B', // Amber
            '#10B981', // Emerald
            '#EF4444', // Red
            '#3B82F6', // Blue
            '#EC4899', // Pink
        ];

        $backgroundColors = collect($revenueByCountry)->keys()->map(function ($key, $index) use ($colors) {
            return $colors[$index % count($colors)];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenueByCountry->values(),
                    'backgroundColor' => $backgroundColors->toArray(),
                ],
            ],
            'labels' => $revenueByCountry->keys()->map(fn ($code) => Countries::getName($code))->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
            'elements' => [
                'arc' => [
                    'borderWidth' => 0,
                ],
            ],
        ];
    }

    public function getDescription(): ?string
    {
        return 'Geographic distribution of revenue based on customer countries.';
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
