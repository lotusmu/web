<?php

use App\Enums\PaymentProvider;
use App\Http\Controllers\Payment\PayPalController;
use App\Http\Controllers\Payment\PrimeController;
use App\Http\Controllers\Payment\StripeController;
use App\Models\Payment\Order;
use App\Services\Payment\PaymentGatewayFactory;
use Illuminate\Http\Request;

Route::name('checkout.')->group(function () {
    // Webhook routes with CSRF exception in bootstrap/app.php
    Route::middleware(['throttle:60,1'])->group(function () {
        Route::post('stripe/webhook', [StripeController::class, 'handleWebhook'])
            ->name('webhook.stripe');

        Route::post('webhook/paypal', [PayPalController::class, 'webhook'])
            ->name('paypal.webhook');

        Route::post('webhook/prime', [PrimeController::class, 'webhook'])
            ->name('prime.webhook')
            ->middleware('valid-prime-webhook-ip');
    });

    // Success/Cancel routes
    Route::middleware(['auth', 'verified', 'throttle:6,1'])->group(function () {
        // Stripe checkout routes
        Route::get('success', function (Request $request) {
            return redirect()
                ->route('dashboard')
                ->with('toast', [
                    'text' => __('Your tokens have been successfully added to your account.'),
                    'heading' => __('Purchase Successful'),
                    'variant' => 'success',
                ]);
        })->name('success');

        Route::get('cancel/{order}', function (Order $order, PaymentGatewayFactory $factory) {
            $gateway = $factory->create(PaymentProvider::STRIPE);
            $gateway->cancelOrder($order);

            return redirect()
                ->route('donate')
                ->with('toast', [
                    'text' => __('Payment was cancelled. Your account has not been charged.'),
                    'heading' => __('Payment Cancelled'),
                    'variant' => 'warning',
                ]);
        })->name('cancel');

        // PayPal specific routes
        Route::prefix('paypal')->name('paypal.')->group(function () {
            Route::get('process/{order}', [PayPalController::class, 'process'])
                ->name('process');

            Route::get('success', [PayPalController::class, 'success'])
                ->name('success');

            Route::get('cancel/{order}', [PayPalController::class, 'cancel'])
                ->name('cancel');
        });

        // Prime specific routes
        Route::prefix('prime')->name('prime.')->group(function () {
            Route::get('success/{order}', [PrimeController::class, 'success'])
                ->name('success');

            Route::get('cancel/{order}', [PrimeController::class, 'cancel'])
                ->name('cancel');
        });
    });
});
