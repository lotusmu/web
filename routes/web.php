<?php

use App\Actions\Partner\PartnerRouteHandler;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Public routes

Volt::route('/', 'pages.guest.home.index')
    ->name('guest.home');

Volt::route('/files', 'pages.guest.files.index')
    ->name('files');

Volt::route('/schedule', 'pages.guest.schedule.index')
    ->name('schedule');

Volt::route('/catalog', 'pages.guest.catalog.index')
    ->name('catalog');

Route::prefix('rankings')->group(function () {
    Volt::route('/', 'pages.guest.rankings.index')
        ->name('rankings');

    Volt::route('/archive', 'pages.guest.rankings.players.archive')
        ->name('rankings.archive');
});

Volt::route('/character/{name}', 'pages.guest.profile.character')
    ->name('character');

Volt::route('/guild/{name}', 'pages.guest.profile.guild')
    ->name('guild');

Volt::route('/terms', 'pages.guest.legal.terms')
    ->name('terms');

Volt::route('/privacy', 'pages.guest.legal.privacy')
    ->name('privacy');

Volt::route('/refund', 'pages.guest.legal.refund')
    ->name('refund');

Volt::route('/guidelines', 'pages.guest.legal.guidelines')
    ->name('guidelines');

Route::prefix('articles')->group(function () {
    Volt::route('/', 'pages.guest.articles.index')
        ->name('articles');

    Volt::route('/{article:slug}', 'pages.guest.articles.show')
        ->middleware('article.published')
        ->name('articles.show');
});

Route::prefix('server')->group(function () {
    Volt::route('/overview', 'pages.guest.server.overview')
        ->name('server.overview');
});

// Profile route
Volt::route('/profile', 'pages.profile.index')
    ->middleware(['auth'])
    ->name('profile');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Volt::route('dashboard', 'pages.dashboard.index')
        ->name('dashboard');

    // Wallet
    Volt::route('wallet', 'pages.wallet.index')
        ->name('wallet');

    // Entries
    Volt::route('event-entries', 'pages.entries.index')
        ->name('entries');

    // Castle Siege group
    Route::prefix('castle-siege')->group(function () {
        Volt::route('/', 'pages.castle.index')
            ->name('castle');
    });

    // VIP routes group
    Route::prefix('vip')->group(function () {
        Volt::route('/', 'pages.vip.index')
            ->middleware('vip.only')
            ->name('vip');
        Volt::route('/purchase', 'pages.vip.purchase')
            ->middleware('non.vip.only')
            ->name('vip.purchase');
    });

    // Stealth Mode
    Volt::route('stealth', 'pages.stealth.index')
        ->name('stealth');

    // Donate
    Volt::route('donate', 'pages.donate.index')
        ->name('donate');

    // Activities
    Volt::route('activities', 'pages.activities.index')
        ->name('activities');

    // Support routes group
    Route::prefix('support')->group(function () {
        Volt::route('/', 'pages.support.index')
            ->name('support');
        Volt::route('/create-ticket', 'pages.support.create-ticket')
            ->name('support.create-ticket');
        Volt::route('/ticket/{ticket}', 'pages.support.show-ticket')
            ->name('support.show-ticket');
    });

    Route::prefix('partners')->group(function () {
        Route::get('/', PartnerRouteHandler::class)->name('partners.index');
        Route::get('/apply', PartnerRouteHandler::class)->name('partners.apply');

        Volt::route('/application', 'pages.partners.application')
            ->middleware('partner.application.check')
            ->name('partners.application');

        Volt::route('/status', 'pages.partners.status')->name('partners.status');
        Volt::route('/dashboard', 'pages.partners.dashboard')
            ->middleware('partner')
            ->name('partners.dashboard');
    });

});

// Authentication routes
require __DIR__.'/auth.php';

// Payment routes
require __DIR__.'/payment.php';
