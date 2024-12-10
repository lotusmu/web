<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStatsWidget;
use App\Filament\Resources\OrderResource\Widgets\OrderStatusDistributionChart;
use App\Filament\Resources\OrderResource\Widgets\RevenueByCountryChart;
use App\Filament\Resources\OrderResource\Widgets\RevenueByProviderChart;
use App\Models\Payment\Order;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OrderResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStatsWidget::class,
            RevenueByProviderChart::class,
            RevenueByCountryChart::class,
            OrderStatusDistributionChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 3;
    }

    public function getTabs(): array
    {
        $counts = cache()->remember('order-status-counts', now()->addHour(), function () {
            return Order::query()
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        });

        return [
            'all' => Tab::make('All Orders')
                ->badge(array_sum($counts)),
            'completed' => Tab::make('Completed')
                ->badge($counts[OrderStatus::COMPLETED->value] ?? 0)
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::COMPLETED);
                }),
            'pending' => Tab::make('Pending')
                ->badge($counts[OrderStatus::PENDING->value] ?? 0)
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::PENDING);
                }),
            'failed' => Tab::make('Failed')
                ->badge($counts[OrderStatus::FAILED->value] ?? 0)
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::FAILED);
                }),
            'cancelled' => Tab::make('Cancelled')
                ->badge($counts[OrderStatus::CANCELLED->value] ?? 0)
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::CANCELLED);
                }),
            'expired' => Tab::make('Expired')
                ->badge($counts[OrderStatus::EXPIRED->value] ?? 0)
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::EXPIRED);
                }),
            'refunded' => Tab::make('Refunded')
                ->badge($counts[OrderStatus::REFUNDED->value] ?? 0)
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::REFUNDED);
                }),
        ];
    }
}
