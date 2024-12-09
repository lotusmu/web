<?php

namespace App\Http\Controllers\Payment;

use App\Enums\PaymentProvider;
use App\Interfaces\PaymentGateway;
use App\Services\Payment\PaymentGatewayFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class StripeController extends CashierWebhookController
{
    private readonly PaymentGateway $gateway;

    public function __construct(
        private readonly PaymentGatewayFactory $gatewayFactory
    ) {
        $this->gateway = $gatewayFactory->create(PaymentProvider::STRIPE);
    }

    public function handleWebhook(Request $request): Response|JsonResponse
    {
        try {
            if (! $this->gateway->verifyWebhookSignature($request->getContent(), $request->headers->all())) {
                Log::error('Stripe webhook signature verification failed', [
                    'headers' => $request->headers->all(),
                ]);

                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $payload = json_decode($request->getContent(), true);
            $this->gateway->handleWebhook($payload);

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (Exception $e) {
            Log::error('Stripe webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}
