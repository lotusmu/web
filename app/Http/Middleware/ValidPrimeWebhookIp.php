<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidPrimeWebhookIp
{
    private const ALLOWED_IP = '186.2.162.11';

    public function handle(Request $request, Closure $next)
    {
        if ($request->ip() !== self::ALLOWED_IP) {
            Log::warning('Invalid Prime webhook IP attempt', [
                'ip' => $request->ip(),
                'headers' => $request->headers->all(),
            ]);

            return response()->json(['error' => 'Invalid request source'], 403);
        }

        return $next($request);
    }
}
