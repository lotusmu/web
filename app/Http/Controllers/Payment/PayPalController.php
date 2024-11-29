<?php

namespace App\Http\Controllers\Payment;

use App\Enums\OrderStatus;
use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Services\Payment\PaymentGatewayFactory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PayPalController extends BasePaymentController
{
    public function __construct(PaymentGatewayFactory $gatewayFactory)
    {
        $this->gateway = $gatewayFactory->create(PaymentProvider::PAYPAL);
    }

    public function process(Order $order): RedirectResponse
    {
        try {
            if ($response = $this->validateOrder($order)) {
                return $response;
            }

            return $this->gateway->processOrder($order)
                ? $this->successResponse()
                : $this->errorResponse(__('We couldn\'t complete your payment. Please try again or use a different payment method.'));

        } catch (Exception $e) {
            $this->logError('process', $e, ['order_id' => $order->id]);

            return $this->errorResponse(__('We encountered a technical issue. Please try again or contact support if the problem persists.'));
        }
    }

    public function success(Request $request): RedirectResponse
    {
        try {
            $order = Order::where('payment_id', $request->token)
                ->where('status', OrderStatus::PENDING)
                ->firstOrFail();

            return $this->gateway->processOrder($order)
                ? $this->successResponse()
                : $this->errorResponse(__('We couldn\'t complete your payment. The transaction was declined or cancelled.'));

        } catch (Exception $e) {
            $this->logError('success', $e);

            return $this->errorResponse(__('We couldn\'t verify your payment status. If your account was charged, please contact support.'));
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
            : $this->errorResponse(__('Unable to cancel payment. Please contact support if you see any charges.'));
    }

    public function webhook(Request $request): JsonResponse
    {
        return $this->handleWebhook($request, 'PayPal webhook');
    }
}
