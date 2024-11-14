<?php

use App\Enums\Game\AccountLevel;
use App\Models\User\User;
use App\Models\Utility\VipPackage;
use Carbon\Carbon;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->user->member->tokens = 10000;
    $this->user->member->save();

    $this->package = VipPackage::create([
        'level' => AccountLevel::Bronze,
        'cost' => 1000,
        'duration' => 30,
        'sort_order' => 1,
        'is_best_value' => true,
    ]);

    actingAs($this->user);
});

it('redirects vip users from purchase page', function () {
    $this->user->member->AccountLevel = AccountLevel::Bronze;
    $this->user->member->AccountExpireDate = now()->addDays(30);
    $this->user->member->save();

    $this->get('vip/purchase')->assertRedirect('vip');
});

it('can render purchase page', function () {
    $response = $this->get('vip/purchase');
    $response->assertOk()
        ->assertSeeVolt('pages.vip.purchase');
});

it('can purchase vip package', function () {
    $component = Volt::test('pages.vip.purchase')
        ->call('purchase', $this->package->id);

    $this->user->refresh();

    expect($this->user->member->AccountLevel)->toBe(AccountLevel::Bronze)
        ->and($this->user->member->tokens)->toBe(9000)
        ->and($this->user->member->AccountExpireDate->format('Y-m-d'))
        ->toBe(Carbon::now()->addDays(30)->format('Y-m-d'));
});

it('prevents purchase with insufficient tokens', function () {
    $this->user->member->tokens = 0;
    $this->user->member->save();

    $component = Volt::test('pages.vip.purchase')
        ->call('purchase', $this->package->id);

    $this->user->refresh();

    expect($this->user->member->AccountLevel)->toBe(AccountLevel::Regular)
        ->and($this->user->member->tokens)->toBe(0);
});

it('can render package card', function () {
    Volt::test('pages.vip.package-card', [
        'package' => $this->package,
        'isFeatured' => true,
    ])
        ->assertSee($this->package->level->getLabel())
        ->assertSee($this->package->cost)
        ->assertSee($this->package->duration);
});

it('properly styles featured package', function () {
    $component = Volt::test('pages.vip.package-card', [
        'package' => $this->package,
        'isFeatured' => true,
    ]);

    $html = $component->html();
    expect($html)->toContain('sm:col-span-2');
});
