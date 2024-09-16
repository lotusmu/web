<?php

use App\Models\Content\Download;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

it('can create a download with local storage', function () {
    $download = Download::factory()->local()->create();

    expect($download)->toBeInstanceOf(Download::class)
        ->and($download->storage_type)->toBe('local')
        ->and($download->local_file)->toBeString()->toStartWith('downloads/')
        ->and($download->external_url)->toBeNull();
});

it('can create a download with external storage', function () {
    $download = Download::factory()->external()->create();

    expect($download)->toBeInstanceOf(Download::class)
        ->and($download->storage_type)->toBe('external')
        ->and($download->external_url)->toBeString()->toStartWith('http')
        ->and($download->local_file)->toBeNull();
});

it('generates correct file url for local storage', function () {
    $download = Download::factory()->local()->create();

    expect($download->file_url)->toBe(Storage::url($download->local_file));
});

it('generates correct file url for external storage', function () {
    $download = Download::factory()->external()->create();

    expect($download->file_url)->toBe($download->external_url);
});

it('generates uuid for id', function () {
    $download = Download::factory()->create();

    expect($download->id)->toBeString()->toHaveLength(36);
});

it('has fillable attributes', function () {
    $fillable = ['name', 'storage_type', 'local_file', 'external_url'];

    expect(Download::factory()->create()->getFillable())->toBe($fillable);
});
