<?php

namespace App\Http\Middleware;

use App\Actions\HandleReferralSurvey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ReferralSurveyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Skip if not a GET request or for specific routes
        if (! $request->isMethod('GET') || $this->shouldSkipCheck($request)) {
            return $next($request);
        }

        // Skip if already determined in this session
        if (Session::has('show_referral_survey')) {
            return $next($request);
        }

        $user = Auth::user();
        if ($user) {
            $action = new HandleReferralSurvey;

            // Check if survey should be shown
            $shouldShow = $action->shouldShowSurvey($user);

            // Store the result in session
            Session::put('show_referral_survey', $shouldShow);

            if ($shouldShow) {
                // Mark that the survey has been shown
                $action->markAsShown($user);
            }
        }

        return $next($request);
    }

    /**
     * Determine if the survey check should be skipped for this request.
     */
    private function shouldSkipCheck(Request $request): bool
    {
        // Skip if user is not authenticated
        if (! Auth::check()) {
            return true;
        }

        // Skip for non-HTML responses or API routes
        if ($request->expectsJson() || $request->is('api/*')) {
            return true;
        }

        return false;
    }
}
