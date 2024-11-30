<?php

namespace App\Actions\Payment;

use Illuminate\Http\RedirectResponse;

class HandlePaymentSuccess
{
    public function handle(array $data = []): RedirectResponse
    {
        return redirect()
            ->route('dashboard')
            ->with('toast', [
                'text' => $data['message'] ?? __('Your tokens have been successfully added to your account.'),
                'heading' => $data['heading'] ?? __('Purchase Successful'),
                'variant' => 'success',
            ]);
    }
}
