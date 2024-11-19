<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\Utility\ResourceType;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayPalController extends Controller
{
    // PayPalController.php
    public function checkout(Order $order)
    {
        if ($order->status !== OrderStatus::PENDING) {
            return redirect()->route('donate');
        }

        // Check if order expired
        if ($order->expires_at->isPast()) {
            $order->update(['status' => OrderStatus::EXPIRED]);

            return redirect()->route('donate')->with('error', 'Order expired');
        }

        try {
            $response = $this->getPayPalHttpClient()->post('/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $order->id,
                    'amount' => [
                        'currency_code' => $order->currency,
                        'value' => number_format($order->amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => route('paypal.success', ['token' => '%id%']),
                    'cancel_url' => route('paypal.cancel', $order->id),
                ],
            ]);

            if ($response->failed()) {
                return back()->with('error', 'Payment initialization failed');
            }

            $order->update(['payment_id' => $response->json()['id']]);

            return redirect($response->json()['links'][1]['href']);
        } catch (Exception) {
            return back()->with('error', 'Payment system error');
        }
    }

    public function success(Request $request)
    {
        try {
            $paypalOrderId = $request->token;
            $order = Order::where('payment_id', $paypalOrderId)
                ->where('status', OrderStatus::PENDING)
                ->firstOrFail();

            $response = $this->getPayPalHttpClient()->post(
                "/v2/checkout/orders/{$paypalOrderId}/capture",
                ['json' => []]
            );

            if ($response->successful()) {
                $order->update([
                    'status' => OrderStatus::COMPLETED,
                    'payment_data' => $response->json(),
                ]);

                $order->user->resource(ResourceType::TOKENS)->increment($order->package->tokens_amount);

                return redirect()->route('dashboard')->with('success', 'Payment completed');
            }

            return redirect()->route('dashboard')->with('error', 'Payment failed');
        } catch (Exception) {
            return redirect()->route('dashboard')->with('error', 'Payment error');
        }
    }

    public function cancel(Order $order)
    {
        $order->update(['status' => OrderStatus::FAILED]);

        return redirect()->route('donate');
    }

    private function getPayPalHttpClient()
    {
        $baseUrl = config('services.paypal.mode') === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';

        return Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode(
                config('services.paypal.client_id').':'.config('services.paypal.secret')
            ),
        ])->baseUrl($baseUrl);
    }
}
