<?php

namespace App\Http\Controllers\Payment;

use App\Actions\Payment\HandlePaymentCancel;
use App\Actions\Payment\HandlePaymentError;
use App\Actions\Payment\HandlePaymentSuccess;
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
    public function __construct(
        private readonly PaymentGatewayFactory $gatewayFactory,
        private readonly HandlePaymentSuccess $paymentSuccess,
        protected readonly HandlePaymentError $paymentError,
        private readonly HandlePaymentCancel $paymentCancel
    ) {
        $this->setGateway($gatewayFactory->create(PaymentProvider::PAYPAL));
    }

    public function process(Order $order): RedirectResponse
    {
        try {
            if ($response = $this->validateOrder($order)) {
                return $response;
            }

            return $this->getGateway()->processOrder($order)
                ? $this->paymentSuccess->handle()
                : $this->paymentError->handle(__('Payment failed'));

        } catch (Exception $e) {
            $this->logError('process', $e, ['order_id' => $order->id]);

            return $this->paymentError->handle(
                __('We encountered a technical issue. Please try again or contact support if the problem persists.')
            );
        }
    }

    public function success(Request $request): RedirectResponse
    {
        try {
            $order = Order::where('payment_id', $request->token)
                ->where('status', OrderStatus::PENDING)
                ->firstOrFail();

            return $this->getGateway()->processOrder($order)
                ? $this->paymentSuccess->handle()
                : $this->paymentError->handle(
                    __('We couldn\'t complete your payment. The transaction was declined or cancelled.')
                );

        } catch (Exception $e) {
            $this->logError('success', $e);

            return $this->paymentError->handle(
                __('We couldn\'t verify your payment status. If your account was charged, please contact support.')
            );
        }
    }

    public function cancel(Order $order): RedirectResponse
    {
        return $this->getGateway()->cancelOrder($order)
            ? $this->paymentCancel->handle()
            : $this->paymentError->handle(
                __('Unable to cancel payment. Please contact support if you see any charges.')
            );
    }

    public function webhook(Request $request): JsonResponse
    {
        try {
            if (! $this->getGateway()->verifyWebhookSignature($request->getContent(), $request->headers->all())) {
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $this->getGateway()->handleWebhook(json_decode($request->getContent(), true));

            return response()->json(['message' => 'Webhook processed successfully']);

        } catch (Exception $e) {
            $this->logError('webhook', $e, [
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}
