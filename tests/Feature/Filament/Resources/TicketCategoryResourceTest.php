<?php

use App\Filament\Resources\TicketCategoryResource;
use App\Filament\Resources\TicketCategoryResource\Pages\ListTicketCategories;
use App\Models\Ticket\TicketCategory;
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

        expect($pages)->toHaveCount(1)
            ->and(array_keys($pages))->toBe(['index']);
    });

    it('can render list page', function () {
        $this->get(TicketCategoryResource::getUrl('index'))->assertSuccessful();
    });
});

it('can create a ticket category', function () {
    $newCategory = TicketCategory::factory()->make();

    livewire(ListTicketCategories::class)
        ->callAction('create', data: [
            'name' => $newCategory->name,
        ])
        ->assertHasNoActionErrors();

    $this->assertDatabaseHas('ticket_categories', [
        'name' => $newCategory->name,
    ]);
});

it('can update a ticket category', function () {
    $category = TicketCategory::factory()->create();
    $newData = TicketCategory::factory()->make();

    livewire(ListTicketCategories::class)
        ->callTableAction('edit', $category, data: [
            'name' => $newData->name,
        ])
        ->assertHasNoTableActionErrors();

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
    livewire(ListTicketCategories::class)
        ->callAction('create', data: [
            'name' => '',
        ])
        ->assertHasActionErrors(['name' => 'required']);
});

it('validates max length of name', function () {
    livewire(ListTicketCategories::class)
        ->callAction('create', data: [
            'name' => Str::random(256),
        ])
        ->assertHasActionErrors(['name' => 'max']);
});
