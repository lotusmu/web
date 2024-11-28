<?php

use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PrimeController;
use App\Http\Controllers\StripeController;
use Illuminate\Http\Request;

Route::name('checkout.')->group(function () {
    // Webhook routes with CSRF exception in bootstrap/app.php
    Route::post('stripe/webhook', [StripeController::class, 'handleWebhook'])
        ->name('webhook.stripe');

    Route::post('webhook/paypal', [PayPalController::class, 'webhook'])
        ->name('paypal.webhook');

    Route::post('webhook/prime', [PrimeController::class, 'webhook'])
        ->name('prime.webhook')
        ->middleware('valid-prime-webhook-ip');

    // Success/Cancel routes
    Route::middleware(['auth', 'verified'])->group(function () {
        // Generic checkout routes
        Route::get('success', function (Request $request) {
            return redirect()
                ->route('dashboard')
                ->with('toast', [
                    'text' => __('Your tokens have been successfully added to your account.'),
                    'heading' => __('Purchase Successful'),
                    'variant' => 'success',
                ]);
        })->name('success');

        Route::get('cancel', function () {
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
