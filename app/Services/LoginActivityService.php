<?php

namespace App\Services;

use Illuminate\Http\Request;

class LoginActivityService
{
    public function record(Request $request, $user): void
    {
        activity('auth')
            ->performedOn($user)
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('User logged in');
    }
}
