<?php

use App\Models\Game\Status;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function () {
    $this->status = new Status;
});

it('has correct table name', function () {
    expect($this->status->getTable())->toBe('MEMB_STAT');
});

it('does not use auto-incrementing IDs', function () {
    expect($this->status->getIncrementing())->toBeFalse();
});

it('does not use timestamps', function () {
    expect($this->status->usesTimestamps())->toBeFalse();
});

it('has correct fillable fields', function () {
    $expectedFillable = [
        'memb___id',
        'ConnectStat',
        'ConnectTM',
        'DisConnectTM',
        'OnlineHours',
    ];

    expect($this->status->getFillable())->toBe($expectedFillable);
});

it('casts attributes correctly', function () {
    $casts = $this->status->getCasts();

    expect($casts)
        ->toHaveKey('memb___id', 'string')
        ->toHaveKey('ConnectStat', 'boolean')
        ->toHaveKey('ConnectTM', 'datetime')
        ->toHaveKey('DisConnectTM', 'datetime')
        ->toHaveKey('OnlineHours', 'integer');
});

it('belongs to a user', function () {
    $relation = $this->status->user();

    expect($relation)
        ->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(User::class)
        ->and($relation->getForeignKeyName())->toBe('memb___id')
        ->and($relation->getOwnerKeyName())->toBe('name');
});

it('properly casts datetime fields', function () {
    $user = User::factory()->create();
    $now = now();

    $status = Status::create([
        'memb___id' => $user->name,
        'ConnectStat' => true,
        'ConnectTM' => $now,
        'DisConnectTM' => $now,
        'OnlineHours' => 0,
    ]);

    expect($status->ConnectTM)
        ->toBeInstanceOf(Carbon::class)
        ->and($status->DisConnectTM)
        ->toBeInstanceOf(Carbon::class);
});

it('properly casts boolean field', function () {
    $user = User::factory()->create();

    $status = Status::create([
        'memb___id' => $user->name,
        'ConnectStat' => true,
        'ConnectTM' => now(),
        'DisConnectTM' => now(),
        'OnlineHours' => 0,
    ]);

    expect($status->ConnectStat)
        ->toBeBool()
        ->toBeTrue();
});
