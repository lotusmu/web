<?php

namespace App\Actions\Payment;

use Illuminate\Http\RedirectResponse;

class HandlePaymentCancel
{
    public function handle(array $data = []): RedirectResponse
    {
        return redirect()
            ->route('donate')
            ->with('toast', [
                'text' => $data['message'] ?? __('Payment was cancelled. Your account has not been charged.'),
                'heading' => $data['heading'] ?? __('Payment Cancelled'),
                'variant' => 'warning',
            ]);
    }
}
