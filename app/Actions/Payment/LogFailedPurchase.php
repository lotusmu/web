<?php

namespace App\Actions\Payment;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\Payment\Order;
use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Support\Str;

class LogFailedPurchase
{
    public function handle(Order $order, string $failureReason): void
    {
        activity('token_purchase')
            ->performedOn($order->user)
            ->withProperties([
                'activity_type' => ActivityType::DEFAULT->value,
                'package_name' => $order->package->name,
                'price' => "{$order->amount} {$order->currency}",
                'resource_type' => Str::title(ResourceType::TOKENS->value),
                'failure_reason' => $failureReason,
                ...IdentityProperties::capture(),
            ])
            ->log(':properties.resource_type purchase failed. Reason: :properties.failure_reason');
    }
}
