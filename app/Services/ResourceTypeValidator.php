<?php

namespace App\Services;

use InvalidArgumentException;

class ResourceTypeValidator
{
    public const ALLOWED_RESOURCE_TYPES = ['tokens', 'credits', 'zen'];

    public static function validate(string $resourceType): void
    {
        if (! self::isValid($resourceType)) {
            throw new InvalidArgumentException("Invalid resource type: {$resourceType}");
        }
    }

    public static function isValid(string $resourceType): bool
    {
        return in_array($resourceType, self::ALLOWED_RESOURCE_TYPES);
    }

    public static function isTokens(string $resourceType): bool
    {
        return $resourceType === 'tokens';
    }

    public static function isCredits(string $resourceType): bool
    {
        return $resourceType === 'credits';
    }

    public static function isZen(string $resourceType): bool
    {
        return $resourceType === 'zen';
    }

    public static function ensureIsTokens(string $resourceType): void
    {
        if (! self::isTokens($resourceType)) {
            throw new InvalidArgumentException("Expected resource type to be tokens, got: {$resourceType}");
        }
    }

    public static function ensureIsCredits(string $resourceType): void
    {
        if (! self::isCredits($resourceType)) {
            throw new InvalidArgumentException("Expected resource type to be credits, got: {$resourceType}");
        }
    }

    public static function ensureIsZen(string $resourceType): void
    {
        if (! self::isZen($resourceType)) {
            throw new InvalidArgumentException("Expected resource type to be zen, got: {$resourceType}");
        }
    }
}
