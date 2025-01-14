<?php

namespace App\Http\Middleware;

class EnsureVipOnlyMiddleware
{
    public function handle($request, $closure)
    {
        if (! auth()->user()->hasValidVipSubscription()) {
            return redirect()->route('vip.purchase');
        }

        return $closure($request);
    }
}
