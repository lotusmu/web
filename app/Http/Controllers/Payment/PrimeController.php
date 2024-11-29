<?php

namespace App\Http\Controllers\Payment;

use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Services\Payment\PaymentGatewayFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrimeController extends BasePaymentController
{
    public function __construct(PaymentGatewayFactory $gatewayFactory)
    {
        $this->gateway = $gatewayFactory->create(PaymentProvider::PRIME);
    }

    public function success(Order $order): RedirectResponse
    {
        try {
            if ($response = $this->validateOrder($order)) {
                return $response;
            }

            return $this->gateway->processOrder($order)
                ? $this->successResponse()
                : $this->errorResponse(__('Payment verification failed'));

        } catch (Exception $e) {
            $this->logError('success callback', $e, ['order' => $order->id]);

            return $this->errorResponse(__('Payment verification failed'));
        }
    }

    public function cancel(Order $order): RedirectResponse
    {
        return $this->gateway->cancelOrder($order)
            ? redirect()
                ->route('donate')
                ->with('toast', [
                    'text' => __('Payment process was cancelled. Your account has not been charged.'),
                    'heading' => __('Payment Cancelled'),
                    'variant' => 'warning',
                ])
            : $this->errorResponse(__('Unable to cancel payment'));
    }

    public function webhook(Request $request): Response|JsonResponse
    {
        try {
            if (! $this->gateway->verifyWebhookSignature($request->getContent(), $request->headers->all())) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $this->gateway->handleWebhook(json_decode($request->getContent(), true));

            return response('OK'); // Prime requires plain text response

        } catch (Exception $e) {
            $this->logError('Prime webhook', $e);

            return response('ERROR', 500);
        }
    }
}
