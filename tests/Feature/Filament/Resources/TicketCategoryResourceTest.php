<?php

use App\Filament\Resources\TicketCategoryResource;
use App\Filament\Resources\TicketCategoryResource\Pages\ListTicketCategories;
use App\Models\TicketCategory;
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

it('uses correct model', function () {
    expect(TicketCategoryResource::getModel())->toBe(TicketCategory::class);
});

it('has correct navigation settings', function () {
    expect(TicketCategoryResource::getNavigationGroup())->toBe('Support')
        ->and(TicketCategoryResource::getNavigationSort())->toBe(2)
        ->and(TicketCategoryResource::getNavigationLabel())->toBe('Categories');
});

describe('pages', function () {
    it('has correct pages', function () {
        $pages = TicketCategoryResource::getPages();

        expect($pages)->toHaveCount(3)
            ->and(array_keys($pages))->toBe(['index', 'create', 'edit']);
    });

    it('can render list page', function () {
        $this->get(TicketCategoryResource::getUrl('index'))->assertSuccessful();
    });

    it('can render edit page', function () {
        $category = TicketCategory::factory()->create();
        $this->get(TicketCategoryResource::getUrl('edit', ['record' => $category]))->assertSuccessful();
    });

    it('can render create page', function () {
        $this->get(TicketCategoryResource::getUrl('create'))->assertSuccessful();
    });
});

it('can create a ticket category', function () {
    $newCategory = TicketCategory::factory()->make();

    livewire(TicketCategoryResource\Pages\CreateTicketCategory::class)
        ->fillForm([
            'name' => $newCategory->name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('ticket_categories', [
        'name' => $newCategory->name,
    ]);
});

it('can update a ticket category', function () {
    $category = TicketCategory::factory()->create();
    $newData = TicketCategory::factory()->make();

    livewire(TicketCategoryResource\Pages\EditTicketCategory::class, [
        'record' => $category->getKey(),
    ])
        ->fillForm([
            'name' => $newData->name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('ticket_categories', [
        'id' => $category->id,
        'name' => $newData->name,
    ]);
});

it('can delete a ticket category', function () {
    $category = TicketCategory::factory()->create();

    livewire(ListTicketCategories::class)
        ->callTableBulkAction('delete', [$category->id]);

    $this->assertDatabaseMissing('ticket_categories', [
        'id' => $category->id,
    ]);
});

it('validates required fields', function () {
    livewire(TicketCategoryResource\Pages\CreateTicketCategory::class)
        ->fillForm([
            'name' => '',
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('validates max length of name', function () {
    livewire(TicketCategoryResource\Pages\CreateTicketCategory::class)
        ->fillForm([
            'name' => Str::random(256),
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'max']);
});
