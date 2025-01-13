<?php

namespace App\Http\Middleware;

use App\Enums\Game\AccountLevel;

class EnsureVipAccessMiddleware
{
    public function handle($request, $closure)
    {
        $user = auth()->user();
        $isVip = $user->member->AccountLevel !== AccountLevel::Regular;
        $hasValidSubscription = $isVip && now()->lessThan($user->member->AccountExpireDate);

        if ($request->routeIs('vip') && ! $hasValidSubscription) {
            return redirect()->route('vip.purchase');
        }

        if ($request->routeIs('vip.purchase') && $hasValidSubscription) {
            return redirect()->route('vip');
        }

        return $closure($request);
    }
}
