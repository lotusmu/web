<?php

use App\Models\Game\Character;
use App\Models\Game\Status;
use App\Models\User\User;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->member->wallet->zen = 100000;
    $this->user->member->wallet->save();

    Status::create([
        'memb___id' => $this->user->name,
        'ConnectStat' => false,
    ]);

    $this->sourceCharacter = Character::factory()->create([
        'AccountID' => $this->user->member->memb___id,
        'Money' => 50000,
    ]);

    $this->destinationCharacter = Character::factory()->create([
        'AccountID' => $this->user->member->memb___id,
        'Money' => 25000,
    ]);

    actingAs($this->user);
});

it('transfer zen can be rendered', function () {
    $response = $this->get('wallet');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.wallet.transfer-zen');

    $response = $this->get('wallet?tab=transfer-zen');
    $response->assertOk();
});

it('can transfer from wallet to character', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'wallet')
        ->set('destination', 'character')
        ->set('destinationCharacter', $this->destinationCharacter->Name)
        ->set('amount', 10000);

    $component->call('transfer');

    $component->assertHasNoErrors();

    $this->user->refresh();
    $this->destinationCharacter->refresh();

    expect($this->user->member->wallet->zen)->toBe(90000)
        ->and($this->destinationCharacter->Money)->toBe(35000);
});

it('can transfer from character to wallet', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'character')
        ->set('sourceCharacter', $this->sourceCharacter->Name)
        ->set('destination', 'wallet')
        ->set('amount', 10000);

    $component->call('transfer');

    $component->assertHasNoErrors();

    $this->user->refresh();
    $this->sourceCharacter->refresh();

    expect($this->sourceCharacter->Money)->toBe(40000)
        ->and($this->user->member->wallet->zen)->toBe(110000);
});

it('can transfer between characters', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'character')
        ->set('sourceCharacter', $this->sourceCharacter->Name)
        ->set('destination', 'character')
        ->set('destinationCharacter', $this->destinationCharacter->Name)
        ->set('amount', 10000);

    $component->call('transfer');

    $component->assertHasNoErrors();

    $this->sourceCharacter->refresh();
    $this->destinationCharacter->refresh();

    expect($this->sourceCharacter->Money)->toBe(40000)
        ->and($this->destinationCharacter->Money)->toBe(35000);
});

it('validates minimum amount', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'wallet')
        ->set('destination', 'character')
        ->set('destinationCharacter', $this->destinationCharacter->Name)
        ->set('amount', 0);

    $component->call('transfer');

    $component->assertHasErrors(['amount']);
});

it('prevents transfer with insufficient balance', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'character')
        ->set('sourceCharacter', $this->sourceCharacter->Name)
        ->set('destination', 'wallet')
        ->set('amount', 60000);

    $component->call('transfer');

    $this->sourceCharacter->refresh();
    $this->user->refresh();

    expect($this->sourceCharacter->Money)->toBe(50000)
        ->and($this->user->member->wallet->zen)->toBe(100000);
});

it('prevents transfer to same character', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'character')
        ->set('sourceCharacter', $this->sourceCharacter->Name)
        ->set('destination', 'character')
        ->set('destinationCharacter', $this->sourceCharacter->Name)
        ->set('amount', 10000);

    $component->call('transfer');

    $this->sourceCharacter->refresh();

    expect($this->sourceCharacter->Money)->toBe(50000);
});

it('prevents transfer when source character not found', function () {
    $initialWalletZen = $this->user->member->wallet->zen;

    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'character')
        ->set('sourceCharacter', 'NonExistentChar')
        ->set('destination', 'wallet')
        ->set('amount', 10000);

    $component->call('transfer');

    expect($this->user->member->wallet->zen)->toBe($initialWalletZen)
        ->and(Character::where('Name', 'NonExistentChar')->exists())->toBeFalse();
});

it('prevents transfer when destination character not found', function () {
    $initialWalletZen = $this->user->member->wallet->zen;

    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'wallet')
        ->set('destination', 'character')
        ->set('destinationCharacter', 'NonExistentChar')
        ->set('amount', 10000);

    $component->call('transfer');

    expect($this->user->member->wallet->zen)->toBe($initialWalletZen)
        ->and(Character::where('Name', 'NonExistentChar')->exists())->toBeFalse();
});

it('prevents transfer exceeding max zen limit', function () {
    $this->destinationCharacter->Money = 1999999000;
    $this->destinationCharacter->save();

    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'wallet')
        ->set('destination', 'character')
        ->set('destinationCharacter', $this->destinationCharacter->Name)
        ->set('amount', 2000000);

    $component->call('transfer');

    $this->destinationCharacter->refresh();
    $this->user->refresh();

    expect($this->destinationCharacter->Money)->toBe(1999999000)
        ->and($this->user->member->wallet->zen)->toBe(100000);
});

it('prevents transfer when user is online', function () {
    Status::where('memb___id', $this->user->name)->update(['ConnectStat' => true]);

    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'wallet')
        ->set('destination', 'character')
        ->set('destinationCharacter', $this->destinationCharacter->Name)
        ->set('amount', 10000);

    $component->call('transfer');

    $this->user->refresh();
    $this->destinationCharacter->refresh();

    expect($this->user->member->wallet->zen)->toBe(100000)
        ->and($this->destinationCharacter->Money)->toBe(25000);
});

it('validates source selection', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', '')
        ->set('destination', 'character')
        ->set('destinationCharacter', $this->destinationCharacter->Name)
        ->set('amount', 10000);

    $component->call('transfer');

    $component->assertHasErrors(['source']);
});

it('validates destination selection', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'wallet')
        ->set('destination', '')
        ->set('amount', 10000);

    $component->call('transfer');

    $component->assertHasErrors(['destination']);
});

it('requires source character when source is character', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'character')
        ->set('destination', 'wallet')
        ->set('sourceCharacter', '')
        ->set('amount', 10000);

    $component->call('transfer');

    $component->assertHasErrors(['sourceCharacter']);
});

it('requires destination character when destination is character', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'wallet')
        ->set('destination', 'character')
        ->set('destinationCharacter', '')
        ->set('amount', 10000);

    $component->call('transfer');

    $component->assertHasErrors(['destinationCharacter']);
});

it('dispatches resourcesUpdated event on successful transfer', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'wallet')
        ->set('destination', 'character')
        ->set('destinationCharacter', $this->destinationCharacter->Name)
        ->set('amount', 10000);

    $component->call('transfer');

    $component->assertDispatched('resourcesUpdated');
});

it('resets form after successful transfer', function () {
    $component = Volt::test('pages.wallet.transfer-zen')
        ->set('source', 'wallet')
        ->set('destination', 'character')
        ->set('destinationCharacter', $this->destinationCharacter->Name)
        ->set('amount', 10000);

    $component->call('transfer');

    expect($component->get('source'))->toBe('')
        ->and($component->get('destination'))->toBe('')
        ->and($component->get('sourceCharacter'))->toBe('')
        ->and($component->get('destinationCharacter'))->toBe('')
        ->and($component->get('amount'))->toBe(0);
});
