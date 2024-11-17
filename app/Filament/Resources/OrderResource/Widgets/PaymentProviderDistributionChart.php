<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Enums\PaymentProvider;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class PaymentProviderDistributionChart extends ChartWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '200px';

    protected static ?string $heading = 'Order Distribution by Payment Provider';

    protected function getData(): array
    {
        $providers = Order::query()
            ->selectRaw('payment_provider, COUNT(*) as total')
            ->groupBy('payment_provider')
            ->pluck('total', 'payment_provider');

        $colorMapping = [
            PaymentProvider::STRIPE->value => '#635BFF',
            PaymentProvider::PAYPAL->value => '#0070E0',
        ];

        $backgroundColors = $providers->keys()->map(function ($provider) use ($colorMapping) {
            return $colorMapping[$provider] ?? '#000000';
        });

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $providers->values(),
                    'backgroundColor' => $backgroundColors->toArray(),
                ],
            ],
            'labels' => $providers->keys()->map(fn ($provider) => PaymentProvider::from($provider)->getLabel())->toArray(),
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
        return 'Distribution of orders across different payment providers.';
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
