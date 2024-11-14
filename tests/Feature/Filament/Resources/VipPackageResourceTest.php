<?php

use App\Enums\Game\AccountLevel;
use App\Filament\Resources\VipPackageResource;
use App\Models\Utility\VipPackage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->package = VipPackage::create([
        'level' => AccountLevel::Bronze,
        'duration' => 30,
        'cost' => 1000,
        'is_best_value' => true,
        'sort_order' => 1,
    ]);
});

it('can render index page', function () {
    $this->get(VipPackageResource::getUrl('index'))->assertSuccessful();
});

it('can render create page', function () {
    $this->get(VipPackageResource::getUrl('create'))->assertSuccessful();
});

it('can render edit page', function () {
    $this->get(VipPackageResource::getUrl('edit', ['record' => $this->package]))
        ->assertSuccessful();
});

it('can create vip package', function () {
    livewire(VipPackageResource\Pages\CreateVipPackage::class)
        ->fillForm([
            'level' => AccountLevel::Silver,
            'duration' => 60,
            'cost' => 2000,
            'is_best_value' => false,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('vip_packages', [
        'level' => AccountLevel::Silver->value,
        'duration' => 60,
        'cost' => 2000,
        'is_best_value' => false,
    ]);
});

it('can edit vip package', function () {
    livewire(VipPackageResource\Pages\EditVipPackage::class, [
        'record' => $this->package->getKey(),
    ])
        ->fillForm([
            'duration' => 90,
            'cost' => 3000,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->package->refresh();

    expect($this->package)
        ->duration->toBe(90)
        ->cost->toBe(3000);
});

it('validates required fields', function () {
    livewire(VipPackageResource\Pages\CreateVipPackage::class)
        ->fillForm([
            'level' => null,
            'duration' => null,
            'cost' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['level', 'duration', 'cost']);
});

it('formats table columns correctly', function () {
    livewire(VipPackageResource\Pages\ListVipPackages::class)
        ->assertCanSeeTableRecords([$this->package])
        ->assertSee('30 days')
        ->assertSee('1000 tokens');
});

it('shows boolean badge for best value', function () {
    livewire(VipPackageResource\Pages\ListVipPackages::class)
        ->assertTableColumnExists('is_best_value');
});
