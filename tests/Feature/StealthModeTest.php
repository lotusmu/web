<?php

use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\User\User;
use App\Models\Utility\Setting;
use Carbon\Carbon;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();

    Setting::create([
        'group' => OperationType::STEALTH->value,
        'settings' => [
            'stealth' => [
                'cost' => 1000,
                'duration' => 7,
                'resource' => ResourceType::CREDITS->value,
            ],
        ],
    ]);

    $this->user->member->wallet->credits = 10000;
    $this->user->member->wallet->save();

    actingAs($this->user);
});

it('stealth page can be rendered', function () {
    $response = $this->get('stealth');

    $response->assertOk()
        ->assertSeeVolt('pages.stealth.index');
});

it('can enable stealth mode', function () {
    $component = Volt::test('pages.stealth.index')
        ->call('enable');

    $this->user->refresh();

    expect($this->user->hasActiveStealth())->toBeTrue()
        ->and($this->user->member->wallet->credits)->toBe(9000)
        ->and($this->user->stealth->expires_at->format('Y-m-d'))
        ->toBe(Carbon::now()->addDays(7)->format('Y-m-d'));
});

it('can extend stealth mode', function () {
    $component = Volt::test('pages.stealth.index')
        ->call('enable');

    $this->user->refresh();
    $initialExpiryDate = $this->user->stealth->expires_at;

    $component->call('extend');

    $this->user->refresh();

    expect($this->user->stealth->expires_at->format('Y-m-d'))
        ->toBe($initialExpiryDate->addDays(7)->format('Y-m-d'))
        ->and($this->user->member->wallet->credits)->toBe(8000);
});

it('prevents enabling when already active', function () {
    $component = Volt::test('pages.stealth.index')
        ->call('enable');

    $this->user->refresh();
    $initialCredits = $this->user->member->wallet->credits;
    $initialExpiry = $this->user->stealth->expires_at;

    $component->call('enable');

    $this->user->refresh();

    expect($this->user->member->wallet->credits)->toBe($initialCredits)
        ->and($this->user->stealth->expires_at)->toEqual($initialExpiry);
});

it('prevents extending when not active', function () {
    $component = Volt::test('pages.stealth.index')
        ->call('extend');

    $this->user->refresh();

    expect($this->user->hasActiveStealth())->toBeFalse()
        ->and($this->user->member->wallet->credits)->toBe(10000);
});

it('prevents enabling with insufficient funds', function () {
    $this->user->member->wallet->credits = 0;
    $this->user->member->wallet->save();

    $component = Volt::test('pages.stealth.index')
        ->call('enable');

    expect($this->user->hasActiveStealth())->toBeFalse();
});
