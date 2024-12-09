<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Payment\Order;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupOldOrdersCommand extends Command
{
    protected $signature = 'orders:cleanup {--months=3 : How many months old orders to keep}';

    protected $description = 'Remove old expired and failed orders';

    public function handle(): void
    {
        $months = $this->option('months');
        $cutoffDate = now()->subMonths($months);

        try {
            $count = Order::query()
                ->whereIn('status', [
                    OrderStatus::EXPIRED->value,
                    OrderStatus::FAILED->value,
                    OrderStatus::CANCELLED->value,
                ])
                ->where('created_at', '<', $cutoffDate)
                ->chunk(100, function ($orders) {
                    foreach ($orders as $order) {
                        $order->statusHistory()->create([
                            'from_status' => $order->status,
                            'to_status' => OrderStatus::DELETED->value,
                            'reason' => 'Automated cleanup of old orders',
                        ]);

                        $order->delete();
                    }
                });

            Log::info('Old orders cleanup completed', [
                'deleted_count' => $count,
                'cutoff_date' => $cutoffDate->toDateTimeString(),
            ]);

            $this->info("Successfully cleaned up old orders older than {$months} months.");

        } catch (Exception $e) {
            Log::error('Order cleanup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error('Failed to cleanup old orders: '.$e->getMessage());
        }
    }
}
