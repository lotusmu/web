<?php

use App\Livewire\Pages\App\Activities\Activities;
use App\Livewire\Pages\App\Castle\Castle;
use App\Livewire\Pages\App\Dashboard\Dashboard;
use App\Livewire\Pages\App\Donate\Donate;
use App\Livewire\Pages\App\Entries\Entries;
use App\Livewire\Pages\App\Partners\Apply;
use App\Livewire\Pages\App\Partners\Partners;
use App\Livewire\Pages\App\Partners\Status;
use App\Livewire\Pages\App\Profile\Profile;
use App\Livewire\Pages\App\Stealth\Stealth;
use App\Livewire\Pages\App\Support\CreateSupportTicket;
use App\Livewire\Pages\App\Support\ShowSupportTicket;
use App\Livewire\Pages\App\Support\Support;
use App\Livewire\Pages\App\Vip\Purchase;
use App\Livewire\Pages\App\Vip\Vip;
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
Route::get('/profile', Profile::class)
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
        Route::get('/', Castle::class)
            ->name('castle');
    });

    // VIP routes group
    Route::prefix('vip')->group(function () {
        Route::get('/', Vip::class)
            ->middleware('vip.only')
            ->name('vip');
        Route::get('/purchase', Purchase::class)
            ->middleware('non.vip.only')
            ->name('vip.purchase');
    });

    // Stealth Mode
    Route::get('stealth', Stealth::class)
        ->name('stealth');

    // Donate
    Route::get('donate', Donate::class)
        ->name('donate');

    // Activities
    Route::get('activities', Activities::class)
        ->name('activities');

    // Support routes group
    Route::prefix('support')->group(function () {
        Route::get('/', Support::class)
            ->name('support');
        Route::get('/create-ticket', CreateSupportTicket::class)
            ->name('support.create-ticket');
        Route::get('/ticket/{ticket}', ShowSupportTicket::class)
            ->name('support.show-ticket');
    });

    Route::prefix('partners')->group(function () {
        Route::get('/', Partners::class)
            ->middleware('partner.application.check')
            ->name('partners.index');

        Route::get('/apply', Apply::class)
            ->middleware('partner.application.check')
            ->name('partners.apply');

        Route::get('/status', Status::class)
            ->name('partners.status');

        Route::get('/dashboard', \App\Livewire\Pages\App\Partners\Dashboard::class)
            ->middleware('partner')
            ->name('partners.dashboard');
    });

});

// Authentication routes
require __DIR__.'/auth.php';

// Payment routes
require __DIR__.'/payment.php';
