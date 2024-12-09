<?php

namespace App\Console\Commands;

use App\Actions\Payment\UpdateOrderStatus;
use App\Enums\OrderStatus;
use App\Models\Payment\Order;
use Illuminate\Console\Command;

class ExpireOrdersCommand extends Command
{
    protected $signature = 'orders:expire';

    protected $description = 'Expire pending orders that have passed their expiration time';

    public function __construct(
        private readonly UpdateOrderStatus $updateOrderStatus
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        Order::query()
            ->where('status', OrderStatus::PENDING)
            ->where('expires_at', '<', now())
            ->chunk(100, function ($orders) {
                foreach ($orders as $order) {
                    $this->updateOrderStatus->handle(
                        order: $order,
                        newStatus: OrderStatus::EXPIRED,
                        paymentData: ['expired_at' => now()]
                    );
                }
            });
    }
}
