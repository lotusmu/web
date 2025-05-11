<?php

namespace App\Support\Notifications;

use App\Enums\Ticket\TicketStatus;
use App\Enums\Utility\ResourceType;

class NotificationTranslator
{
    /**
     * Translate parameters in notification data
     */
    public static function translateParameters(array $parameters): array
    {
        foreach ($parameters as $key => $value) {
            // Handle ticket status
            if ($key === 'status' && is_string($value) && TicketStatus::tryFrom($value)) {
                $parameters[$key] = TicketStatus::from($value)->getLabel();

                continue;
            }

            // Handle resource type
            if ($key === 'resource' && is_string($value)) {
                $resourceValues = ['tokens', 'credits', 'zen'];
                if (in_array(strtolower($value), $resourceValues)) {
                    $resourceType = ResourceType::from(strtolower($value));
                    $parameters[$key] = $resourceType->getLabel();

                    continue;
                }
            }
        }

        return $parameters;
    }
}
