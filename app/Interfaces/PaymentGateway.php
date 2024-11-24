<?php

namespace App\Interfaces;

use App\Models\Order;
use App\Models\TokenPackage;
use App\Models\User\User;

interface PaymentGateway
{
    public function initiateCheckout(User $user, TokenPackage $package): mixed;

    public function handleWebhook(array $payload): mixed;

    public function processOrder(Order $order): bool;

    public function verifyWebhookSignature(string $payload, array $headers): bool;

    public function cancelOrder(Order $order): bool;
}
