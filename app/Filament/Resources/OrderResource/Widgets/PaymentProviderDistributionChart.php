<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class PaymentProviderDistributionChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '250px';

    protected static ?string $heading = 'Order Distribution by Payment Provider';

    protected function getData(): array
    {
        $providers = Order::query()
            ->selectRaw('payment_provider, COUNT(*) as total')
            ->groupBy('payment_provider')
            ->pluck('total', 'payment_provider');

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $providers->values(),
                ],
            ],
            'labels' => $providers->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
