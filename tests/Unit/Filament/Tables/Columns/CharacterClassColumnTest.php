<?php

use App\Filament\Tables\Columns\CharacterClassColumn;

beforeEach(function () {
    $this->column = CharacterClassColumn::make('class');
});

it('has default image size of 40', function () {
    expect($this->column->getImageSize())->toBe(40);
});

it('can set and get custom image size', function () {
    $this->column->imageSize(60);
    expect($this->column->getImageSize())->toBe(60);
});

it('can set image size using a closure', function () {
    $this->column->imageSize(fn () => 80);
    expect($this->column->getImageSize())->toBe(80);
});

it('includes image size in extra attributes', function () {
    $this->column->imageSize(50);
    $extraAttributes = $this->column->getExtraAttributes();
    expect($extraAttributes)->toHaveKey('imageSize', 50);
});
