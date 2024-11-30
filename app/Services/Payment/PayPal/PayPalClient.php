<?php

namespace App\Services\Payment\PayPal;

use Illuminate\Support\Facades\Http;

class PayPalClient
{
    private $client;

    public function __construct()
    {
        $baseUrl = config('services.paypal.mode') === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';

        $this->client = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode(
                config('services.paypal.client_id').':'.config('services.paypal.secret')
            ),
        ])->baseUrl($baseUrl);
    }

    public function get()
    {
        return $this->client;
    }
}
