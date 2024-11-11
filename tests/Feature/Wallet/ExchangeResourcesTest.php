<?php

use App\Models\User\User;
use App\Models\Utility\Setting;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->member->tokens = 1000;
    $this->user->member->wallet->credits = 0;
    $this->user->member->save();
    $this->user->member->wallet->save();

    actingAs($this->user);
});

it('exchange resources can be rendered', function () {
    $response = $this->get('wallet');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.wallet.exchange-tokens');

    $response = $this->get('wallet?tab=exchange-tokens');
    $response->assertOk();
});

it('can exchange tokens for credits', function () {
    $component = Volt::test('pages.wallet.exchange-tokens')
        ->set('amount', 100);

    $component->call('exchange');

    $component->assertHasNoErrors();

    $this->user->refresh();

    expect($this->user->member->tokens)->toBe(900)
        ->and($this->user->member->wallet->credits)->toBe(100);
});

it('validates minimum amount', function () {
    $component = Volt::test('pages.wallet.exchange-tokens')
        ->set('amount', 0);

    $component->call('exchange');

    $component->assertHasErrors(['amount']);
});

it('prevents exchange with insufficient balance', function () {
    $component = Volt::test('pages.wallet.exchange-tokens')
        ->set('amount', 2000);

    $component->call('exchange');

    $this->user->refresh();

    expect($this->user->member->tokens)->toBe(1000)
        ->and($this->user->member->wallet->credits)->toBe(0);
});

it('applies tax to exchanged amount', function () {
    Setting::create([
        'group' => 'exchange',
        'settings' => ['exchange' => ['rate' => 10]],
    ]);

    $amount = 100;
    $taxRate = 10;
    $taxAmount = (int) round($amount * ($taxRate / 100));
    $exchangedAmount = $amount - $taxAmount;

    $component = Volt::test('pages.wallet.exchange-tokens')
        ->set('amount', $amount);

    $component->call('exchange');

    $component->assertHasNoErrors();

    $this->user->refresh();

    expect($this->user->member->tokens)->toBe(1000 - $amount)
        ->and($this->user->member->wallet->credits)->toBe($exchangedAmount);
});

it('dispatches resourcesUpdated event on successful exchange', function () {
    $component = Volt::test('pages.wallet.exchange-tokens')
        ->set('amount', 100);

    $component->call('exchange');

    $component->assertDispatched('resourcesUpdated');
});

it('resets form after successful exchange', function () {
    $component = Volt::test('pages.wallet.exchange-tokens')
        ->set('amount', 100);

    $component->call('exchange');

    expect($component->get('amount'))->toBeNull();
});

it('requires amount to be provided', function () {
    $component = Volt::test('pages.wallet.exchange-tokens');

    $component->call('exchange');

    $component->assertHasErrors(['amount']);
});
