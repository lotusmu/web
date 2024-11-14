<?php

use App\Enums\Utility\OperationType;
use App\Enums\Utility\ResourceType;
use App\Filament\Resources\SettingResource;
use App\Models\Utility\Setting;

use function Pest\Livewire\livewire;

it('can render index page', function () {
    $this->get(SettingResource::getUrl('index'))->assertSuccessful();
});

it('can list settings', function () {
    $setting = Setting::create([
        'group' => OperationType::PK_CLEAR->value,
        'settings' => [
            'pk_clear' => [
                'cost' => 1000,
                'resource' => ResourceType::ZEN->value,
            ],
        ],
    ]);

    livewire(SettingResource\Pages\ListSettings::class)
        ->assertCanSeeTableRecords([$setting]);
});

it('can create pk clear setting using modal', function () {
    $component = livewire(SettingResource\Pages\ListSettings::class);

    $component->mountAction('create')
        ->setActionData([
            'group' => OperationType::PK_CLEAR->value,
            'settings' => [
                'pk_clear' => [
                    'cost' => 1000,
                    'resource' => ResourceType::ZEN->value,
                ],
            ],
        ])
        ->callAction('create');

    $this->assertDatabaseHas('settings', [
        'group' => OperationType::PK_CLEAR->value,
    ]);
});

it('validates required fields in create modal', function () {
    $component = livewire(SettingResource\Pages\ListSettings::class);

    $component->mountAction('create')
        ->setActionData([
            'group' => OperationType::PK_CLEAR->value,
            'settings' => [
                'pk_clear' => [
                    'cost' => '',
                    'resource' => '',
                ],
            ],
        ])
        ->callAction('create')
        ->assertHasActionErrors(['settings.pk_clear.cost', 'settings.pk_clear.resource']);
});

it('validates numeric constraints in create modal', function () {
    $component = livewire(SettingResource\Pages\ListSettings::class);

    $component->mountAction('create')
        ->setActionData([
            'group' => OperationType::TRANSFER->value,
            'settings' => [
                'transfer' => [
                    'rate' => 101,  // Over 100%
                ],
            ],
        ])
        ->callAction('create')
        ->assertHasActionErrors(['settings.transfer.rate']);
});

it('can edit setting using modal', function () {
    $setting = Setting::create([
        'group' => OperationType::PK_CLEAR->value,
        'settings' => [
            'pk_clear' => [
                'cost' => 1000,
                'resource' => ResourceType::ZEN->value,
            ],
        ],
    ]);

    livewire(SettingResource\Pages\ListSettings::class)
        ->mountTableAction(name: 'edit', record: $setting)
        ->setTableActionData([
            'group' => OperationType::PK_CLEAR->value,
            'settings' => [
                'pk_clear' => [
                    'cost' => 2000,
                    'resource' => ResourceType::ZEN->value,
                ],
            ],
        ])
        ->callMountedTableAction();

    $this->assertDatabaseHas('settings', [
        'id' => $setting->id,
        'settings->pk_clear->cost' => 2000,
    ]);
});

it('can delete setting', function () {
    $setting = Setting::create([
        'group' => OperationType::PK_CLEAR->value,
        'settings' => [
            'pk_clear' => [
                'cost' => 1000,
                'resource' => ResourceType::ZEN->value,
            ],
        ],
    ]);

    livewire(SettingResource\Pages\ListSettings::class)
        ->callTableAction('delete', $setting);

    $this->assertModelMissing($setting);
});

it('shows correct group labels in table', function () {
    $pkClearSetting = Setting::create([
        'group' => OperationType::PK_CLEAR->value,
        'settings' => ['pk_clear' => ['cost' => 1000, 'resource' => ResourceType::ZEN->value]],
    ]);

    $stealthSetting = Setting::create([
        'group' => OperationType::STEALTH->value,
        'settings' => ['stealth' => ['cost' => 1000, 'resource' => ResourceType::TOKENS->value]],
    ]);

    livewire(SettingResource\Pages\ListSettings::class)
        ->assertSee(OperationType::PK_CLEAR->getLabel())
        ->assertSee(OperationType::STEALTH->getLabel());
});
