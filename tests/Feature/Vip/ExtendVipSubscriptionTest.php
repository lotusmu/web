<?php

use App\Enums\Game\AccountLevel;
use App\Models\User\User;
use App\Models\Utility\VipPackage;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->user->member->tokens = 10000;
    $this->user->member->wallet->save();

    $this->package = VipPackage::create([
        'level' => AccountLevel::Bronze,
        'cost' => 1000,
        'duration' => 30,
        'sort_order' => 1,
        'is_best_value' => true,
    ]);

    $this->user->member->AccountLevel = AccountLevel::Bronze;
    $this->user->member->AccountExpireDate = now()->addDays(30);
    $this->user->member->save();

    actingAs($this->user);
});

it('redirects regular users from vip extension page', function () {
    $this->user->member->AccountLevel = AccountLevel::Regular;
    $this->user->member->save();

    $this->get('vip')->assertRedirect('vip/purchase');
});

it('can render extension page', function () {
    $response = $this->get('vip');
    $response->assertOk()
        ->assertSeeVolt('pages.vip.index');
});

it('shows correct account level information', function () {
    $component = Volt::test('pages.vip.index');

    $accountLevel = $component->get('accountLevel');

    expect($accountLevel)->toMatchArray([
        'label' => AccountLevel::Bronze->getLabel(),
        'color' => AccountLevel::Bronze->badgeColor(),
        'expireDate' => $this->user->member->AccountExpireDate,
    ]);
});

it('can extend vip subscription', function () {
    $originalExpiry = $this->user->member->AccountExpireDate;

    $component = Volt::test('pages.vip.index')
        ->set('packageId', $this->package->id)
        ->call('extend');

    $this->user->refresh();

    expect($this->user->member->AccountExpireDate->format('Y-m-d'))
        ->toBe($originalExpiry->addDays(30)->format('Y-m-d'))
        ->and($this->user->member->tokens)->toBe(9000);
});

it('prevents extension with insufficient tokens', function () {
    $this->user->member->tokens = 0;
    $this->user->member->save();

    $originalExpiry = $this->user->member->AccountExpireDate;

    $component = Volt::test('pages.vip.index')
        ->set('packageId', $this->package->id)
        ->call('extend');

    $this->user->refresh();

    expect($this->user->member->AccountExpireDate->format('Y-m-d'))
        ->toBe($originalExpiry->format('Y-m-d'))
        ->and($this->user->member->tokens)->toBe(0);
});
