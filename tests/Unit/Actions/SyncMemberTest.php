<?php

use App\Actions\SyncMember;
use App\Models\User\Member;
use App\Models\User\User;
use Carbon\Carbon;

beforeEach(function () {
    refreshTable('MEMB_INFO', 'gamedb_main');

    $this->syncMemberAction = new SyncMember;

    $this->username = fakeUsername();
    $this->email = fakeEmail();
});

it('creates a new member if one does not exist', function () {
    $this->username = fakeUsername();
    $this->email = fakeEmail();

    $user = User::factory()->create([
        'name' => $this->username,
        'email' => $this->email,
        'password' => 'password',
    ]);

    $this->syncMemberAction->handle($user);

    $this->assertDatabaseHas('MEMB_INFO', [
        'memb___id' => $this->username,
        'mail_addr' => $this->email,
        'memb__pwd' => 'password',
    ], 'gamedb_main');
});

it('updates an existing member', function () {
    $user = User::factory()->create([
        'name' => $this->username,
        'email' => $this->email,
        'password' => 'password',
    ]);

    $user->password = 'password2';
    $user->save();

    $this->syncMemberAction->handle($user);

    $this->assertDatabaseHas('MEMB_INFO', [
        'memb___id' => $this->username,
        'mail_addr' => $this->email,
        'memb__pwd' => 'password2',
    ], 'gamedb_main');

    $user->refresh();
    $newEmail = fakeEmail();

    $user->email = $newEmail;
    $user->save();

    $this->syncMemberAction->handle($user);

    $this->assertDatabaseHas('MEMB_INFO', [
        'memb___id' => $this->username,
        'mail_addr' => $newEmail,
        'memb__pwd' => 'password2',
    ], 'gamedb_main');
});

it('sets default values for new members', function () {
    $user = User::factory()->create();

    $this->syncMemberAction->handle($user);

    $this->assertDatabaseHas('MEMB_INFO', [
        'memb___id' => $user->name,
        'sno__numb' => 1111111111111,
        'appl_days' => 0,
        'mail_chek' => 0,
        'bloc_code' => 0,
        'ctl1_code' => 0,
        'AccountLevel' => 0,
        'tokens' => 0,
    ], 'gamedb_main');

    $member = Member::where('memb___id', $user->name)->first();

    expect($member->AccountExpireDate->isToday())->toBeTrue();
});

it('preserves existing member values when updating', function () {
    // Create initial user and member
    $user = User::factory()->create([
        'name' => $this->username,
        'email' => $this->email,
        'password' => 'password',
    ]);

    $this->syncMemberAction->handle($user);

    // Manually update member with custom values
    $member = Member::where('memb___id', $user->name)->first();
    $customExpireDate = Carbon::now()->addDays(30);

    $member->update([
        'sno__numb' => 9999999999999,
        'appl_days' => 30,
        'mail_chek' => 1,
        'bloc_code' => 1,
        'ctl1_code' => 1,
        'AccountLevel' => 2,
        'tokens' => 100,
        'AccountExpireDate' => $customExpireDate,
    ]);

    // Update user's email and password
    $newEmail = fakeEmail();

    $user->email = $newEmail;
    $user->password = 'new__pwd';
    $user->save();

    $this->syncMemberAction->handle($user);

    $this->assertDatabaseHas('MEMB_INFO', [
        'memb___id' => $this->username,
        'mail_addr' => $newEmail,
        'memb__pwd' => 'new__pwd',
        'sno__numb' => 9999999999999,
        'appl_days' => 30,
        'mail_chek' => 1,
        'bloc_code' => 1,
        'ctl1_code' => 1,
        'AccountLevel' => 2,
        'tokens' => 100,
    ], 'gamedb_main');

    // Check that AccountExpireDate wasn't changed
    $updatedMember = Member::where('memb___id', $user->name)->first();
    expect($updatedMember->AccountExpireDate->isSameDay($customExpireDate))->toBeTrue();
});
