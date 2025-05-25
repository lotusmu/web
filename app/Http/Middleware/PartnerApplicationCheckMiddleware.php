<?php

namespace App\Http\Middleware;

use App\Services\PartnerAccessService;
use Closure;
use Illuminate\Http\Request;

class PartnerApplicationCheckMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $service = new PartnerAccessService(auth()->user());

        if (! $service->canAccessForm()) {
            return redirect()->route($service->getRedirectRoute());
        }

        return $next($request);
    }
}
