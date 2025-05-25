<?php

namespace App\Http\Middleware;

use App\Models\Partner\Partner;
use Closure;
use Illuminate\Http\Request;

class PartnerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $partner = Partner::where('user_id', auth()->id())->first();

        if (! $partner || $partner->status->value !== 'active') {
            return redirect()->route('partners.apply')
                ->with('error', 'You need to be an approved partner to access this page.');
        }

        return $next($request);
    }
}
