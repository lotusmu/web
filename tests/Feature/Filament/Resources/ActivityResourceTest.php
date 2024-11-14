<?php

use App\Filament\Resources\ActivityResource;
use App\Models\User\User;
use Spatie\Activitylog\Models\Activity;

use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->activity = Activity::create([
        'log_name' => 'user_login',
        'description' => 'User logged in',
        'subject_type' => User::class,
        'subject_id' => 1,
        'causer_type' => User::class,
        'causer_id' => 1,
        'properties' => [
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0',
        ],
    ]);
});

it('can render index page', function () {
    $this->get(ActivityResource::getUrl('index'))->assertSuccessful();
});

it('can render view page', function () {
    $this->get(ActivityResource::getUrl('view', ['record' => $this->activity]))
        ->assertSuccessful();
});

it('can filter activities by log name', function () {
    $loginActivity = Activity::create([
        'log_name' => 'login',
        'description' => 'User logged in',
    ]);

    $logoutActivity = Activity::create([
        'log_name' => 'logout',
        'description' => 'User logged out',
    ]);

    livewire(ActivityResource\Pages\ListActivities::class)
        ->assertTableFilterExists('log_name')
        ->filterTable('log_name', 'login')
        ->assertCanSeeTableRecords([$loginActivity])
        ->assertCanNotSeeTableRecords([$logoutActivity]);
});

it('can filter activities by date range', function () {
    $oldActivity = Activity::create([
        'created_at' => now()->subDays(5),
        'description' => 'Old activity',
    ]);

    $newActivity = Activity::create([
        'created_at' => now(),
        'description' => 'New activity',
    ]);

    livewire(ActivityResource\Pages\ListActivities::class)
        ->assertTableFilterExists('created_at');
});

it('formats system causer correctly', function () {
    $testResource = new class extends ActivityResource
    {
        public static function testGetCauserInfo($record)
        {
            return self::getCauserInfo($record);
        }
    };

    $activity = Activity::create([
        'description' => 'System action',
        'causer_type' => null,
        'causer_id' => null,
    ]);

    $causerInfo = $testResource::testGetCauserInfo($activity);

    expect($causerInfo)
        ->toHaveKey('name', 'System')
        ->toHaveKey('color', 'primary')
        ->toHaveKey('icon', null)
        ->toHaveKey('url', null);
});

test('causer info handling', function () {
    $testResource = new class extends ActivityResource
    {
        public static function testGetCauserInfo($record)
        {
            return self::getCauserInfo($record);
        }
    };

    $user = User::factory()->create();

    $userActivity = Activity::create([
        'description' => 'User action',
        'causer_type' => User::class,
        'causer_id' => $user->id,
    ]);

    $systemActivity = Activity::create([
        'description' => 'System action',
        'causer_type' => null,
        'causer_id' => null,
    ]);

    $userInfo = $testResource::testGetCauserInfo($userActivity);
    expect($userInfo)
        ->toHaveKey('name', $user->name)
        ->toHaveKey('color', null)
        ->toHaveKey('icon', 'heroicon-o-arrow-top-right-on-square')
        ->toHaveKey('url', route('filament.admin.resources.members.edit', ['record' => $user->name]));

    $systemInfo = $testResource::testGetCauserInfo($systemActivity);
    expect($systemInfo)
        ->toHaveKey('name', 'System')
        ->toHaveKey('color', 'primary')
        ->toHaveKey('icon', null)
        ->toHaveKey('url', null);
});

it('shows proper columns in table', function () {
    $user = User::factory()->create();

    $activity = Activity::create([
        'description' => 'Test action',
        'causer_type' => User::class,
        'causer_id' => $user->id,
        'properties' => ['ip_address' => '127.0.0.1'],
    ]);

    livewire(ActivityResource\Pages\ListActivities::class)
        ->assertCanSeeTableRecords([$activity])
        ->assertTableColumnExists('description')
        ->assertTableColumnExists('causer.name')
        ->assertTableColumnExists('properties.ip_address')
        ->assertTableColumnExists('created_at');
});

it('can view activity details', function () {
    livewire(ActivityResource\Pages\ListActivities::class)
        ->assertTableActionExists('view');

    $this->get(ActivityResource::getUrl('view', ['record' => $this->activity]))
        ->assertSuccessful()
        ->assertSee($this->activity->description)
        ->assertSee($this->activity->properties['ip_address']);
});
