<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $completedOrders = Order::where('status', OrderStatus::COMPLETED);

        $totalRevenue = $completedOrders->sum('amount');
        $totalOrders = $completedOrders->count();
        $totalCustomers = $completedOrders->distinct('user_id')->count('user_id');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            Stat::make('Total Revenue', '€ '.number_format($totalRevenue, 2))
                ->description('Revenue from completed orders'),

            Stat::make('Total Orders', $totalOrders)
                ->description('Completed orders'),

            Stat::make('Average Order Value', '€ '.number_format($averageOrderValue, 2))
                ->description('Average revenue per completed order'),

            Stat::make('Total Customers', $totalCustomers)
                ->description('Customers with completed orders'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
