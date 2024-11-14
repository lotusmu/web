<?php

use App\Enums\Utility\OperationType;
use App\Facades\Settings;
use App\Models\Concerns\Taxable;
use App\Models\Utility\Setting;
use App\Providers\SettingsServiceProvider;
use App\Support\Settings\SettingsManager;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('can store and retrieve settings', function () {
    Setting::create([
        'group' => 'test',
        'settings' => ['key' => 'value'],
    ]);

    expect(Settings::get('test', 'key'))->toBe('value')
        ->and(Settings::get('test', 'nonexistent', 'default'))->toBe('default');
});

it('caches settings', function () {
    Setting::create([
        'group' => 'test',
        'settings' => ['key' => 'value'],
    ]);

    Settings::get('test', 'key');

    expect(Cache::has('settings.test'))->toBeTrue();
});

it('clears cache on update', function () {
    $setting = Setting::create([
        'group' => 'test',
        'settings' => ['key' => 'value'],
    ]);

    Settings::get('test', 'key');

    $setting->update(['settings' => ['key' => 'new_value']]);

    expect(Cache::has('settings.test'))->toBeFalse()
        ->and(Settings::get('test', 'key'))->toBe('new_value');
});

it('clears cache on delete', function () {
    $setting = Setting::create([
        'group' => 'test',
        'settings' => ['key' => 'value'],
    ]);

    Settings::get('test', 'key');
    $setting->delete();

    expect(Cache::has('settings.test'))->toBeFalse();
});

it('can get entire group', function () {
    Setting::create([
        'group' => 'test',
        'settings' => [
            'key1' => 'value1',
            'key2' => 'value2',
        ],
    ]);

    expect(Settings::group('test'))->toBe([
        'key1' => 'value1',
        'key2' => 'value2',
    ]);
});

it('can flush group', function () {
    Setting::create([
        'group' => 'test',
        'settings' => ['key' => 'value'],
    ]);

    Settings::flush('test');

    expect(Setting::where('group', 'test')->exists())->toBeFalse();
});

it('returns all groups', function () {
    Setting::create(['group' => 'group1', 'settings' => []]);
    Setting::create(['group' => 'group2', 'settings' => []]);

    expect(app(SettingsManager::class)->groups()->toArray())
        ->toBe(['group1', 'group2']);
});

test('taxable trait calculations', function () {
    $class = new class
    {
        use Taxable;

        public function __construct()
        {
            $this->operationType = OperationType::PK_CLEAR;
            $this->initializeTaxable();
        }
    };

    Setting::create([
        'group' => OperationType::PK_CLEAR->value,
        'settings' => [
            'pk_clear' => [
                'cost' => 1000,
                'resource' => 'zen',
            ],
        ],
    ]);

    expect($class->calculateRate(5))->toEqual(5000);
});

test('taxable trait defaults', function () {
    $class = new class
    {
        use Taxable;

        public function getTestResourceType(): string
        {
            return $this->getResourceType();
        }

        public function getTestRate(): float
        {
            return $this->getRate();
        }
    };

    expect($class->calculateRate(100))->toEqual(0)
        ->and($class->getTestResourceType())->toBe('tokens');
});

test('taxable trait defaults and duration behavior', function () {
    $class = new class
    {
        use Taxable;

        public function getTestResourceType(): string
        {
            return $this->getResourceType();
        }

        public function getTestDuration(): int
        {
            return $this->getDuration();
        }

        public function setOperationType(OperationType $type): void
        {
            $this->operationType = $type;
        }
    };

    $class->setOperationType(OperationType::TRANSFER);

    expect($class->getTestResourceType())->toBe('tokens')
        ->and($class->getTestDuration())->toBe(0);

    $class->setOperationType(OperationType::STEALTH);
    Setting::create([
        'group' => OperationType::STEALTH->value,
        'settings' => [
            'stealth' => [
                'duration' => 14,
            ],
        ],
    ]);
    expect($class->getTestDuration())->toBe(14);
});

test('settings manager is registered as singleton', function () {
    $app = app();

    $provider = new SettingsServiceProvider($app);
    $provider->register();

    $instance1 = $app->make(SettingsManager::class);
    $instance2 = $app->make(SettingsManager::class);

    expect($instance1)->toBe($instance2);
});
