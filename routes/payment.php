<?php

use App\Http\Controllers\StripeWebhookController;

// Stripe Webhook
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])->name('cashier.webhook');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout/success', function (Request $request) {
        return redirect()->route('dashboard');
    })->name('checkout.success');

    Route::get('/checkout/cancel', function () {
        return redirect()->route('donate');
    })->name('checkout.cancel');
});
