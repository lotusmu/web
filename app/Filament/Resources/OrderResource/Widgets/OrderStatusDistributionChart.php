<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class OrderStatusDistributionChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '200px';

    protected static ?string $heading = 'Order Distribution by Status';

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getData(): array
    {
        $query = $this->getPageTableQuery();

        $statuses = $query
            ->reorder()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $colorMapping = [
            OrderStatus::PENDING->value => '#3b82f6',   // Blue-500
            OrderStatus::COMPLETED->value => '#10b981', // Emerald-500
            OrderStatus::FAILED->value => '#ef4444',    // Red-500
            OrderStatus::EXPIRED->value => '#f59e0b',   // Amber-500
            OrderStatus::REFUNDED->value => '#d946ef',  // Fuchsia-500
            OrderStatus::CANCELLED->value => '#71717a',  // Zinc-500
        ];

        $backgroundColors = $statuses->keys()->map(function ($status) use ($colorMapping) {
            return $colorMapping[$status] ?? '#000000';
        });

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $statuses->values(),
                    'backgroundColor' => $backgroundColors->toArray(),
                ],
            ],
            'labels' => $statuses->keys()->map(fn ($status) => OrderStatus::from($status)->getLabel())->toArray(),
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
        return 'Distribution of orders by their current status.';
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
