<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;

beforeEach(function () {
    Auth::logout();
});

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.register');
});

test('new users can register', function () {
    $username = fakeUsername();
    $email = fakeEmail();

    $component = Volt::test('pages.auth.register')
        ->set('name', $username)
        ->set('email', $email)
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    $component->call('register');

    $component->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});
