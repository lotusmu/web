<?php

use App\Enums\PaymentProvider;
use App\Models\Payment\TokenPackage;
use App\Services\Payment\PaymentGatewayFactory;
use App\Services\Payment\PayPalGateway;
use App\Services\Payment\StripeGateway;

test('checkout component initiates stripe payment flow', function () {
    $package = TokenPackage::factory()->create();
    $mockUrl = 'https://checkout.stripe.com/test';

    $mockGateway = Mockery::mock(StripeGateway::class);
    $mockGateway->shouldReceive('initiateCheckout')
        ->once()
        ->andReturn((object) ['url' => $mockUrl]);

    $mockFactory = Mockery::mock(PaymentGatewayFactory::class);
    $mockFactory->shouldReceive('create')->andReturn($mockGateway);
    $this->app->instance(PaymentGatewayFactory::class, $mockFactory);

    Livewire::test('pages.donate.index')
        ->set('selectedPackage', $package->id)
        ->set('paymentMethod', PaymentProvider::STRIPE->value)
        ->call('checkout')
        ->assertRedirect($mockUrl);
});

test('checkout component initiates paypal payment flow', function () {
    $package = TokenPackage::factory()->create();
    $mockUrl = 'https://www.sandbox.paypal.com/checkout';

    $mockGateway = Mockery::mock(PayPalGateway::class);
    $mockGateway->shouldReceive('initiateCheckout')
        ->once()
        ->andReturn($mockUrl);

    $mockFactory = Mockery::mock(PaymentGatewayFactory::class);
    $mockFactory->shouldReceive('create')->andReturn($mockGateway);
    $this->app->instance(PaymentGatewayFactory::class, $mockFactory);

    Livewire::test('pages.donate.index')
        ->set('selectedPackage', $package->id)
        ->set('paymentMethod', PaymentProvider::PAYPAL->value)
        ->call('checkout')
        ->assertRedirect($mockUrl);
});

test('checkout component shows available packages', function () {
    $packages = TokenPackage::factory()->count(3)->create();

    $component = Livewire::test('pages.donate.index');

    $component->assertSee($packages->pluck('name')->toArray());
});
