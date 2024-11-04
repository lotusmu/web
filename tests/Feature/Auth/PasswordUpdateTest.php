<?php

namespace Tests\Feature\Auth;

use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt;

test('password can be updated', function () {
    $user = User::factory()->create(['password' => 'password']);

    $this->actingAs($user);

    $component = Volt::test('profile.password')
        ->set('current_password', 'password')
        ->set('password', 'password2')
        ->set('password_confirmation', 'password2')
        ->call('updatePassword');

    $component
        ->assertHasNoErrors()
        ->assertNoRedirect();

    expect(Hash::check('password2', $user->fresh()->password))->toBeTrue()
        ->and($user->fresh()->member->password)->toBe('password2');
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('profile.password')
        ->set('current_password', 'wrong-pwd')
        ->set('password', 'new-pwd')
        ->set('password_confirmation', 'new-pwd')
        ->call('updatePassword');

    $component
        ->assertHasErrors(['current_password'])
        ->assertNoRedirect();
});

test('new password must be confirmed', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);
    $this->actingAs($user);

    $component = Volt::test('profile.password')
        ->set('current_password', 'password')
        ->set('password', 'newpass123')
        ->set('password_confirmation', 'different')
        ->call('updatePassword');

    $component
        ->assertHasErrors(['password' => 'confirmed'])
        ->assertNoRedirect();
});
