<?php

namespace App\Http\Controllers\Payment;

use App\Actions\Payment\HandlePaymentCancel;
use App\Actions\Payment\HandlePaymentError;
use App\Actions\Payment\HandlePaymentSuccess;
use App\Enums\PaymentProvider;
use App\Models\Payment\Order;
use App\Services\Payment\PaymentGatewayFactory;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrimeController extends BasePaymentController
{
    public function __construct(
        private readonly PaymentGatewayFactory $gatewayFactory,
        private readonly HandlePaymentSuccess $paymentSuccess,
        protected readonly HandlePaymentError $paymentError,
        private readonly HandlePaymentCancel $paymentCancel
    ) {
        $this->setGateway($gatewayFactory->create(PaymentProvider::PRIME));
    }

    public function success(Order $order): RedirectResponse
    {
        try {
            if ($response = $this->validateOrder($order)) {
                return $response;
            }

            return $this->getGateway()->processOrder($order)
                ? $this->paymentSuccess->handle()
                : $this->paymentError->handle(__('Payment verification failed'));

        } catch (Exception $e) {
            $this->logError('success', $e, ['order_id' => $order->id]);

            return $this->paymentError->handle(
                __('We encountered a technical issue. Please try again or contact support if the problem persists.')
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

    public function webhook(Request $request): Response
    {
        try {
            if (! $this->getGateway()->verifyWebhookSignature($request->getContent(), $request->headers->all())) {
                $this->logError('webhook', new Exception('Signature verification failed'), [
                    'headers' => $request->headers->all(),
                ]);

                return response('ERROR', 400);
            }

            parse_str($request->getContent(), $data);
            $this->getGateway()->handleWebhook($data);

            return response('OK');
        } catch (Exception $e) {
            $this->logError('webhook', $e, [
                'payload' => $request->all(),
            ]);

            return response('ERROR', 500);
        }
    }
}
