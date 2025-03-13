<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TrackPageViewsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Set first visit time if not set
        if (! Session::has('first_visit_time')) {
            Session::put('first_visit_time', time());
        }

        // Increment page views
        $pageViews = Session::get('page_views', 0);
        Session::put('page_views', $pageViews + 1);

        return $next($request);
    }
}
