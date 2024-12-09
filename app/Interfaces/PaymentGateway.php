<?php

namespace App\Interfaces;

use App\Models\Payment\Order;
use App\Models\Payment\TokenPackage;
use App\Models\User\User;

interface PaymentGateway
{
    public function initiateCheckout(User $user, TokenPackage $package): mixed;

    public function processOrder(Order $order): bool;

    public function handleWebhook(array $payload): mixed;

    public function verifyWebhookSignature(string $payload, array $headers): bool;

    public function cancelOrder(Order $order): bool;

    public function getProviderName(): string;
}
