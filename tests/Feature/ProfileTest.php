<?php

use App\Models\User\User;
use Livewire\Volt\Volt;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/profile');

    $response
        ->assertOk()
        ->assertSeeVolt('profile.update-profile-information-form')
        ->assertSeeVolt('profile.update-password-form');
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $newEmail = fakeEmail();

    $this->actingAs($user);

    $component = Volt::test('profile.update-profile-information-form')
        ->set('email', $newEmail)
        ->call('updateProfileInformation');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $user->refresh();

    $this->assertSame($newEmail, $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('profile.update-profile-information-form')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('profile.delete-user-form')
        ->set('password', 'wrong-pas')
        ->call('deleteUser');

    $component
        ->assertHasErrors('password')
        ->assertNoRedirect();

    $this->assertNotNull($user->fresh());
});
