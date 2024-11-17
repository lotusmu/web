<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

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
