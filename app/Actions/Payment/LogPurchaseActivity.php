<?php

namespace App\Actions\Payment;

use App\Enums\Utility\ActivityType;
use App\Enums\Utility\ResourceType;
use App\Models\Payment\Order;
use App\Support\ActivityLog\IdentityProperties;
use Illuminate\Support\Str;

class LogPurchaseActivity
{
    public function handle(Order $order): void
    {
        activity('token_purchase')
            ->performedOn($order->user)
            ->withProperties([
                'activity_type' => ActivityType::INCREMENT->value,
                'package_name' => $order->package->name,
                'amount' => $order->package->tokens_amount,
                'price' => "{$order->amount} {$order->currency}",
                'resource_type' => Str::title(ResourceType::TOKENS->value),
                ...IdentityProperties::capture(),
            ])
            ->log('Purchased :properties.package_name.');
    }
}
