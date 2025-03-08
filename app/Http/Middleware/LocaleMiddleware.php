<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', 'en');
        app()->setLocale($locale);

        return $next($request);
    }
}
