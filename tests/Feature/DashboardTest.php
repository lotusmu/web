<?php

use App\Enums\Game\Map;
use App\Enums\Game\PkLevel;
use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Models\Game\Character;
use App\Models\Game\Status;
use App\Models\User\User;
use App\Models\Utility\Setting;
use Livewire\Volt\Volt;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->user = User::factory()->create();

    Setting::create([
        'group' => OperationType::PK_CLEAR->value,
        'settings' => [
            'pk_clear' => [
                'cost' => 1000,
                'resource' => ResourceType::ZEN->value,
            ],
        ],
    ]);

    Status::create([
        'memb___id' => $this->user->name,
        'ConnectStat' => false,
    ]);

    $this->character = Character::factory()->create([
        'AccountID' => $this->user->name,
        'PkCount' => 5,
        'Money' => 100000,
        'MapNumber' => Map::Dungeon,
        'MapPosX' => 100,
        'MapPosY' => 100,
    ]);

    actingAs($this->user);
});

it('can be rendered', function () {
    $response = $this->get('dashboard');

    $response->assertOk()
        ->assertSeeVolt('pages.dashboard.index');
});

it('can render character-row component', function () {
    Volt::test('pages.dashboard.character-row', ['character' => $this->character])
        ->assertSee($this->character->Name)
        ->assertSee($this->character->Class->getLabel());
});

it('can unstuck character', function () {
    $component = Volt::test('pages.dashboard.character-row', ['character' => $this->character])
        ->call('unstuck');

    $this->character->refresh();

    expect($this->character->MapNumber)->toBe(Map::Lorencia)
        ->and($this->character->MapPosX)->toBe(125)
        ->and($this->character->MapPosY)->toBe(125);
});

it('prevents unstuck when user is online', function () {
    Status::where('memb___id', $this->user->name)->update(['ConnectStat' => true]);

    $originalMap = $this->character->MapNumber;
    $originalX = $this->character->MapPosX;
    $originalY = $this->character->MapPosY;

    Volt::test('pages.dashboard.character-row', ['character' => $this->character])
        ->call('unstuck');

    $this->character->refresh();

    expect($this->character->MapNumber)->toBe($originalMap)
        ->and($this->character->MapPosX)->toBe($originalX)
        ->and($this->character->MapPosY)->toBe($originalY);
});

it('can clear kills', function () {
    $expectedCost = 5000; // 5 kills * 1000 cost per kill
    $initialMoney = $this->character->Money;

    $component = Volt::test('pages.dashboard.character-row', ['character' => $this->character])
        ->call('clearKills');

    $this->character->refresh();

    expect($this->character->PkCount)->toBe(0)
        ->and($this->character->PkTime)->toBe(0)
        ->and($this->character->PkLevel)->toBe(PkLevel::Commoner)
        ->and($this->character->Money)->toBe($initialMoney - $expectedCost);
});

it('prevents kill clear when insufficient funds', function () {
    Setting::where('group', OperationType::PK_CLEAR->value)->update([
        'settings' => [
            'pk_clear' => [
                'cost' => 50000,
                'resource' => ResourceType::ZEN->value,
            ],
        ],
    ]);

    $originalPkCount = $this->character->PkCount;

    Volt::test('pages.dashboard.character-row', ['character' => $this->character])
        ->call('clearKills');

    $this->character->refresh();

    expect($this->character->PkCount)->toBe($originalPkCount);
});

it('prevents kill clear when no kills', function () {
    $this->character->PkCount = 0;
    $this->character->save();

    $originalMoney = $this->character->Money;

    Volt::test('pages.dashboard.character-row', ['character' => $this->character])
        ->call('clearKills');

    $this->character->refresh();

    expect($this->character->Money)->toBe($originalMoney);
});

it('prevents kill clear when user is online', function () {
    Status::where('memb___id', $this->user->name)->update(['ConnectStat' => true]);

    $originalPkCount = $this->character->PkCount;
    $originalMoney = $this->character->Money;

    Volt::test('pages.dashboard.character-row', ['character' => $this->character])
        ->call('clearKills');

    $this->character->refresh();

    expect($this->character->PkCount)->toBe($originalPkCount)
        ->and($this->character->Money)->toBe($originalMoney);
});

it('uses correct resource type from settings', function () {
    Setting::where('group', OperationType::PK_CLEAR->value)->update([
        'settings' => [
            'pk_clear' => [
                'cost' => 1000,
                'resource' => ResourceType::CREDITS->value,
            ],
        ],
    ]);

    $this->user->member->wallet->credits = 10000;
    $this->user->member->wallet->save();

    $originalCredits = $this->user->member->wallet->credits;
    $expectedCost = 5000; // 5 kills * 1000 cost per kill

    Volt::test('pages.dashboard.character-row', ['character' => $this->character])
        ->call('clearKills');

    $this->user->refresh();

    expect($this->user->member->wallet->credits)->toBe($originalCredits - $expectedCost)
        ->and($this->character->Money)->toBe(100000); // Character money unchanged
});
