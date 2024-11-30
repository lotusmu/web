<?php

namespace App\Services\Payment;

use Exception;
use Illuminate\Support\Facades\Log;

class PaymentLogger
{
    public function logError(string $provider, string $method, Exception $e, array $context = []): void
    {
        Log::error("{$provider} {$method} error", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            ...$context,
        ]);
    }
}
