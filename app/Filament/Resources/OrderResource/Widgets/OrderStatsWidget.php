<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::sum('amount');
        $totalOrders = Order::count();
        $totalCustomers = Order::distinct('user_id')->count('user_id');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            Stat::make('Total Revenue', '€ '.number_format($totalRevenue, 2))
                ->description('Total revenue generated'),

            Stat::make('Total Orders', $totalOrders)
                ->description('Total orders placed'),

            Stat::make('Average Order Value', '€ '.number_format($averageOrderValue, 2))
                ->description('Average revenue per order'),

            Stat::make('Total Customers', $totalCustomers)
                ->description('Unique customers'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
