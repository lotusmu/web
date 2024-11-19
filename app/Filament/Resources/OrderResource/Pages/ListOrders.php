<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStatsWidget;
use App\Filament\Resources\OrderResource\Widgets\OrderStatusDistributionChart;
use App\Filament\Resources\OrderResource\Widgets\RevenueByCountryChart;
use App\Filament\Resources\OrderResource\Widgets\RevenueByProviderChart;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
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
        return [
            'all' => Tab::make('All Orders'),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::COMPLETED);
                }),
            'failed' => Tab::make('Failed')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::FAILED);
                }),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::PENDING);
                }),
            'refunded' => Tab::make('Refunded')
                ->modifyQueryUsing(function ($query) {
                    return $query->where('status', OrderStatus::REFUNDED);
                }),
        ];
    }
}
