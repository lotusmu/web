<?php

use App\Filament\Resources\DownloadResource;
use App\Models\Content\Download;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');
});

describe('pages', function () {
    it('has correct pages', function () {
        $pages = DownloadResource::getPages();

        expect($pages)->toHaveCount(3)
            ->and(array_keys($pages))->toBe(['index', 'create', 'edit']);
    });

    it('can render list page', function () {
        $this->get(DownloadResource::getUrl('index'))->assertSuccessful();
    });

    it('can render edit page', function () {
        $download = Download::factory()->create();
        $this->get(DownloadResource::getUrl('edit', ['record' => $download]))->assertSuccessful();
    });

    it('can render create page', function () {
        $this->get(DownloadResource::getUrl('create'))->assertSuccessful();
    });
});

it('can create a download with local storage', function () {
    $file = UploadedFile::fake()->create('test.zip', 100);

    Livewire::test(DownloadResource\Pages\CreateDownload::class)
        ->set('data.name', 'Test Download')
        ->set('data.storage_type', 'local')
        ->set('data.local_file', $file)
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('downloads', [
        'name' => 'Test Download',
        'storage_type' => 'local',
    ]);

    $download = Download::where('name', 'Test Download')->first();
    $this->assertNotNull($download->local_file);
    $this->assertNull($download->external_url);
});

it('can create a download with external storage', function () {
    Livewire::test(DownloadResource\Pages\CreateDownload::class)
        ->set('data.name', 'External Download')
        ->set('data.storage_type', 'external')
        ->set('data.external_url', 'https://example.com/file.zip')
        ->call('create')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('downloads', [
        'name' => 'External Download',
        'storage_type' => 'external',
        'external_url' => 'https://example.com/file.zip',
    ]);
});

it('can edit an existing download', function () {
    $download = Download::factory()->create();

    Livewire::test(DownloadResource\Pages\EditDownload::class, ['record' => $download->id])
        ->set('data.name', 'Updated Download')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('downloads', [
        'id' => $download->id,
        'name' => 'Updated Download',
    ]);
});

it('validates required fields for local storage', function () {
    Livewire::test(DownloadResource\Pages\CreateDownload::class)
        ->set('data.storage_type', 'local')
        ->call('create')
        ->assertHasErrors(['data.name', 'data.local_file']);
});

it('validates required fields for external storage', function () {
    Livewire::test(DownloadResource\Pages\CreateDownload::class)
        ->set('data.storage_type', 'external')
        ->call('create')
        ->assertHasErrors(['data.name', 'data.external_url']);
});

it('validates file size for local storage', function () {
    $file = UploadedFile::fake()->create('large.zip', 600 * 1024); // 600MB

    Livewire::test(DownloadResource\Pages\CreateDownload::class)
        ->set('data.name', 'Large File')
        ->set('data.storage_type', 'local')
        ->set('data.local_file', $file)
        ->call('create')
        ->assertHasErrors(['data.local_file']);
});

it('validates url format for external storage', function () {
    Livewire::test(DownloadResource\Pages\CreateDownload::class)
        ->set('data.name', 'Invalid URL')
        ->set('data.storage_type', 'external')
        ->set('data.external_url', 'not-a-url')
        ->call('create')
        ->assertHasErrors(['data.external_url']);
});
