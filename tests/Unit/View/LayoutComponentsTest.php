<?php

use App\View\Components\AppLayout;
use App\View\Components\AuthLayout;
use App\View\Components\GuestLayout;
use Illuminate\View\View;

describe('layout components', function () {
    it('renders app layout view', function () {
        $component = new AppLayout;

        expect($component->render())
            ->toBeInstanceOf(View::class)
            ->and($component->render()->getName())->toBe('layouts.app');
    });

    it('renders auth layout view', function () {
        $component = new AuthLayout;

        expect($component->render())
            ->toBeInstanceOf(View::class)
            ->and($component->render()->getName())->toBe('layouts.auth');
    });

    it('renders guest layout view', function () {
        $component = new GuestLayout;

        expect($component->render())
            ->toBeInstanceOf(View::class)
            ->and($component->render()->getName())->toBe('layouts.guest');
    });

    it('can render layouts as blade components', function () {
        $this->blade(
            '<x-app-layout>Test Content</x-app-layout>'
        )->assertSee('Test Content');

        $this->blade(
            '<x-auth-layout>Test Content</x-auth-layout>'
        )->assertSee('Test Content');

        $this->blade(
            '<x-guest-layout>Test Content</x-guest-layout>'
        )->assertSee('Test Content');
    });
});
