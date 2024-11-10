<?php

use App\Enums\Utility\ResourceType;
use App\Models\Game\Character;
use App\Models\User\User;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->sender = User::factory()->create();
    $this->recipient = User::factory()->create();

    $this->sender->member->tokens = 1000;
    $this->sender->member->wallet->credits = 1000;
    $this->sender->member->wallet->zen = 100000;
    $this->sender->member->save();
    $this->sender->member->wallet->save();

    $this->recipientCharacter = Character::factory()->forUser($this->recipient)->create();

    actingAs($this->sender);
});

it('send resources can be rendered', function () {
    $response = $this->get('wallet');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.wallet.send-gift');
});

it('users can send tokens', function () {
    $component = Volt::test('pages.wallet.send-gift')
        ->set('resourceType', ResourceType::TOKENS)
        ->set('amount', 100)
        ->set('recipient', $this->recipientCharacter->Name);

    $component->call('transfer');

    $component->assertHasNoErrors();

    $this->sender->refresh();
    $this->recipient->refresh();

    expect($this->sender->member->tokens)->toBe(900)
        ->and($this->recipient->member->tokens)->toBe(100);
});

it('users can send credits', function () {
    $component = Volt::test('pages.wallet.send-gift')
        ->set('resourceType', ResourceType::CREDITS)
        ->set('amount', 100)
        ->set('recipient', $this->recipientCharacter->Name);

    $component->call('transfer');

    $component->assertHasNoErrors();

    $this->sender->refresh();
    $this->recipient->refresh();

    expect($this->sender->member->wallet->credits)->toBe(900)
        ->and($this->recipient->member->wallet->credits)->toBe(100);
});

it('users can send zen', function () {
    $component = Volt::test('pages.wallet.send-gift')
        ->set('resourceType', ResourceType::ZEN)
        ->set('amount', 1000)
        ->set('recipient', $this->recipientCharacter->Name);

    $component->call('transfer');

    $component->assertHasNoErrors();

    $this->sender->refresh();
    $this->recipient->refresh();

    expect($this->sender->member->wallet->zen)->toBe(99000)
        ->and($this->recipient->member->wallet->zen)->toBe(1000);
});

it('validates minimum amount', function () {
    $component = Volt::test('pages.wallet.send-gift')
        ->set('resourceType', ResourceType::TOKENS)
        ->set('amount', 50)
        ->set('recipient', $this->recipientCharacter->Name);

    $component->call('transfer');

    $component->assertHasErrors(['amount']);
});

it('validates insufficient balance', function () {
    $component = Volt::test('pages.wallet.send-gift')
        ->set('resourceType', ResourceType::TOKENS)
        ->set('amount', 2000)
        ->set('recipient', $this->recipientCharacter->Name);

    $component->call('transfer');

    $component->assertHasErrors(['amount']);
});

it('validates recipient exists', function () {
    $component = Volt::test('pages.wallet.send-gift')
        ->set('resourceType', ResourceType::TOKENS)
        ->set('amount', 100)
        ->set('recipient', 'NonExistentChar');

    $component->call('transfer');

    $component->assertHasErrors(['recipient']);
});

it('applies tax to sent amount', function () {
    $amount = 100;
    $taxRate = 10;
    $taxAmount = round($amount * ($taxRate / 100));
    $totalAmount = $amount + $taxAmount;

    $component = Volt::test('pages.wallet.send-gift')
        ->set('resourceType', ResourceType::TOKENS)
        ->set('amount', $amount)
        ->set('recipient', $this->recipientCharacter->Name);

    $component->call('transfer');

    $component->assertHasNoErrors();

    $this->sender->refresh();
    $this->recipient->refresh();

    expect($this->sender->member->tokens)->toBe(1000 - $totalAmount)
        ->and($this->recipient->member->tokens)->toBe($amount);
});
