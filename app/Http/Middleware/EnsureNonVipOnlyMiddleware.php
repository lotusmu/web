<?php

namespace App\Http\Middleware;

class EnsureNonVipOnlyMiddleware
{
    public function handle($request, $closure)
    {
        if (auth()->user()->hasValidVipSubscription()) {
            return redirect()->route('vip');
        }

        return $closure($request);
    }
}
