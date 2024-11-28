<?php

namespace App\Console\Commands;

use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Console\Command;
use InvalidArgumentException;

class SimulatePrimeWebhook extends Command
{
    protected $signature = 'prime:webhook
                          {order : Order ID}
                          {event=success : Event type (success/fail/refund)}';

    protected $description = 'Simulate Prime payment webhook events';

    public function handle(): int
    {
        $order = Order::findOrFail($this->argument('order'));
        $event = $this->argument('event');

        $this->info("Found order: ID {$order->id}, Status: {$order->status->value}, Provider: {$order->payment_provider->value}");

        $gateway = PaymentGatewayFactory::create(PaymentProvider::PRIME);

        $payload = $this->generatePayload($order, $event);
        $this->info('Generated payload: '.json_encode($payload, JSON_PRETTY_PRINT));

        $result = $gateway->handleWebhook($payload);

        $this->info('Webhook handled. Result: '.json_encode($result));

        $order->refresh();
        $this->info('Final order status: '.$order->status->value);

        return self::SUCCESS;
    }

    private function generatePayload(Order $order, string $event): array
    {
        $basePayload = [
            'project' => config('services.prime.project_id'),
            'orderID' => $order->id,
            'date_pay' => now()->timestamp,
            'innerID' => $order->id,
            'sum' => $order->amount,
            'currency' => $order->currency,
            'payed_from' => '4242424242424242',
        ];

        return match ($event) {
            'success' => [
                'action' => 'order_payed',
                'payWay' => '1',
                'webmaster_profit' => $order->amount * 0.97, // Simulating 3% fee
                'email' => $order->user->email,  // Add this line
                ...$basePayload,
            ],
            'fail' => [
                'action' => 'order_cancel',
                ...$basePayload,
            ],
            default => throw new InvalidArgumentException("Unknown event type: {$event}")
        };
    }
}
