<?php

namespace App\Http\Controllers\Payment;

use App\Enums\PaymentProvider;
use App\Services\Payment\PaymentGatewayFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Log;

class StripeController extends CashierWebhookController
{
    public function handleWebhook(Request $request): Response|JsonResponse
    {
        $gateway = PaymentGatewayFactory::create(PaymentProvider::STRIPE);

        try {
            if (! $gateway->verifyWebhookSignature($request->getContent(), $request->headers->all())) {
                Log::error('Stripe webhook signature verification failed');

                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $payload = json_decode($request->getContent(), true);
            $gateway->handleWebhook($payload);

            return new Response('Webhook Handled', 200);

        } catch (Exception $e) {
            Log::error('Stripe webhook error', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}
