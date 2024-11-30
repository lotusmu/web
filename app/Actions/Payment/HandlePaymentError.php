<?php

namespace App\Actions\Payment;

use Illuminate\Http\RedirectResponse;

class HandlePaymentError
{
    public function handle(string $message, array $data = []): RedirectResponse
    {
        return redirect()
            ->route('donate')
            ->with('toast', [
                'text' => $message,
                'heading' => $data['heading'] ?? __('Payment Issue'),
                'variant' => 'danger',
            ]);
    }
}
