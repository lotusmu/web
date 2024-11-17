<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueByProviderChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '250px';

    protected static ?string $heading = 'Revenue by Payment Provider';

    protected function getData(): array
    {
        $revenueByProvider = Order::query()
            ->selectRaw('payment_provider, SUM(amount) as total_revenue')
            ->groupBy('payment_provider')
            ->pluck('total_revenue', 'payment_provider');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenueByProvider->values(),
                    'backgroundColor' => ['#1e3a8a', '#10b981', '#f43f5e'], // Optional: Customize bar colors
                ],
            ],
            'labels' => $revenueByProvider->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
