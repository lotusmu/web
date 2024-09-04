<?php

namespace App\Support\ActivityLog;

use Illuminate\Support\Facades\Request;

class IdentityProperties
{
    public static function capture(): array
    {
        return [
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ];
    }
}
