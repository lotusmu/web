<?php

use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PayPalWebhookController;
use App\Http\Controllers\StripeWebhookController;

// Stripe Webhook
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('cashier.webhook');

// PayPal
Route::get('/paypal/checkout/{order}', [PayPalController::class, 'checkout'])
    ->name('paypal.checkout');

Route::post('/paypal/webhook', [PayPalWebhookController::class, 'handleWebhook'])
    ->name('paypal.webhook');

Route::post('/paypal/webhook/capture', [PayPalWebhookController::class, 'handleCapture'])
    ->name('paypal.webhook.capture');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout/success', function (Request $request) {
        return redirect()->route('dashboard');
    })->name('checkout.success');

    Route::get('/checkout/cancel', function () {
        return redirect()->route('donate');
    })->name('checkout.cancel');

    Route::get('/paypal/checkout/{order}', [PayPalController::class, 'checkout'])
        ->name('paypal.checkout');

    Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel/{order}', [PayPalController::class, 'cancel'])->name('paypal.cancel');
});
