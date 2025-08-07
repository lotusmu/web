<?php

use App\Livewire\Pages\App\Activities\Activities;
use App\Livewire\Pages\App\Dashboard\Dashboard;
use App\Livewire\Pages\App\Donate\Donate;
use App\Livewire\Pages\App\Entries\Entries;
use App\Livewire\Pages\Guest\Articles\Articles;
use App\Livewire\Pages\Guest\Catalog\Catalog;
use App\Livewire\Pages\Guest\Content\Streams;
use App\Livewire\Pages\Guest\Files\Files;
use App\Livewire\Pages\Guest\Home;
use App\Livewire\Pages\Guest\Legal\Guidelines;
use App\Livewire\Pages\Guest\Legal\Privacy as PrivacyAlias;
use App\Livewire\Pages\Guest\Legal\Refund as RefundAlias;
use App\Livewire\Pages\Guest\Legal\Terms;
use App\Livewire\Pages\Guest\Profile\CharacterProfile;
use App\Livewire\Pages\Guest\Profile\GuildProfile;
use App\Livewire\Pages\Guest\Rankings\Players\Archive;
use App\Livewire\Pages\Guest\Rankings\Rankings;
use App\Livewire\Pages\Guest\Server\Overview;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Public routes
Route::get('/', Home::class)
    ->name('guest.home');

Route::get('/files', Files::class)
    ->name('files');

Route::get('/catalog', Catalog::class)
    ->name('catalog');

Route::prefix('rankings')->group(function () {
    Route::get('/', Rankings::class)
        ->name('rankings');

    Route::get('/archive', Archive::class)
        ->name('rankings.archive');
});

Route::get('/character/{name}', CharacterProfile::class)
    ->name('character');

Route::get('/guild/{name}', GuildProfile::class)
    ->name('guild');

Route::get('/terms', Terms::class)
    ->name('terms');

Route::get('/privacy', PrivacyAlias::class)
    ->name('privacy');

Route::get('/refund', RefundAlias::class)
    ->name('refund');

Route::get('/guidelines', Guidelines::class)
    ->name('guidelines');

Route::prefix('articles')->group(function () {
    Route::get('/', Articles::class)
        ->name('articles');

    Volt::route('/{article:slug}', 'pages.guest.articles.show')
        ->middleware('article.published')
        ->name('articles.show');
});

Route::prefix('content')->group(function () {
    Route::get('/streams', Streams::class)
        ->name('content.streams');
});

Route::prefix('server')->group(function () {
    Route::get('/overview', Overview::class)
        ->name('server.overview');
});

// Profile route
Volt::route('/profile', 'pages.profile.index')
    ->middleware(['auth'])
    ->name('profile');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', Dashboard::class)
        ->name('dashboard');

    // Wallet
    Volt::route('wallet', 'pages.wallet.index')
        ->name('wallet');

    // Entries
    Route::get('event-entries', Entries::class)
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
    Route::get('donate', Donate::class)
        ->name('donate');

    // Activities
    Route::get('activities', Activities::class)
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
        Volt::route('/', 'pages.partners.index')
            ->middleware('partner.application.check')
            ->name('partners.index');

        Volt::route('/apply', 'pages.partners.apply')
            ->middleware('partner.application.check')
            ->name('partners.apply');

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
