<?php

namespace App\Support\ActivityLog;

use Illuminate\Support\Facades\Request;

class IdentityProperties
{
    public static function capture(): array
    {
        return [
            'ip_address' => self::resolveIpAddress(),
            'user_agent' => Request::userAgent(),
        ];
    }

    private static function resolveIpAddress(): string
    {
        $ip = Request::ip();

        // If we already have an IPv4 address, just return it
        if (! str_contains($ip, ':')) {
            return $ip;
        }

        // Check if it's an IPv4-mapped IPv6 address
        if (preg_match('/(\d+\.\d+\.\d+\.\d+)$/', $ip, $matches)) {
            return $matches[1];
        }

        // No IPv4 available, so return the original IPv6
        return $ip;
    }
}
