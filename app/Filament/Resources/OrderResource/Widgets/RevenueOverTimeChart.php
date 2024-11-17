<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueOverTimeChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '250px';

    protected static ?string $heading = 'Revenue Over Time';

    protected function getData(): array
    {
        $revenue = Order::query()
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->date => $row->total]);

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenue->values(),
                ],
            ],
            'labels' => $revenue->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
