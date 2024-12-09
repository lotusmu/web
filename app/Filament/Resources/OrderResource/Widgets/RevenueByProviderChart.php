<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class RevenueByProviderChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '200px';

    protected static ?string $heading = 'Revenue by Payment Provider';

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        $revenueByProvider = $query
            ->reorder()
            ->where('status', OrderStatus::COMPLETED)
            ->selectRaw('payment_provider, SUM(amount) as total_revenue')
            ->groupBy('payment_provider')
            ->pluck('total_revenue', 'payment_provider');

        $colorMapping = [
            PaymentProvider::STRIPE->value => '#a855f7',
            PaymentProvider::PAYPAL->value => '#3b82f6',
            PaymentProvider::PRIME->value => '#f59e0b',
        ];

        $backgroundColors = $revenueByProvider->keys()->map(function ($provider) use ($colorMapping) {
            return $colorMapping[$provider] ?? '#000000';
        });

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $revenueByProvider->values(),
                    'backgroundColor' => $backgroundColors->toArray(),
                ],
            ],
            'labels' => $revenueByProvider->keys()->map(fn ($provider) => PaymentProvider::from($provider)->getLabel())->toArray(),
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
        return 'Total revenue breakdown by payment method.';
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
