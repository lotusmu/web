<?php

use App\Console\Commands\CleanEventEntriesCommand;
use App\Console\Commands\CleanupGuildMarksCommand;
use App\Console\Commands\DistributeCastleSiegePrizesCommand;
use App\Console\Commands\ExpireOrdersCommand;
use App\Console\Commands\ProcessWeeklyRankingsCommand;
use App\Http\Middleware\CheckArticlePublishedMiddleware;
use App\Http\Middleware\CheckUserBannedMiddleware;
use App\Http\Middleware\EnsureNonVipOnlyMiddleware;
use App\Http\Middleware\EnsureVipOnlyMiddleware;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\ValidPrimeWebhookIpMiddleware;
use App\Services\GameServerStatusService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            CheckUserBannedMiddleware::class,
            LocaleMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'webhook/paypal',
            'webhook/prime',
        ]);

        $middleware->alias([
            'vip.only' => EnsureVipOnlyMiddleware::class,
            'non.vip.only' => EnsureNonVipOnlyMiddleware::class,
            'article.published' => CheckArticlePublishedMiddleware::class,
            'valid-prime-webhook-ip' => ValidPrimeWebhookIpMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(ExpireOrdersCommand::class)
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command(CleanupGuildMarksCommand::class)
            ->daily()
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command(DistributeCastleSiegePrizesCommand::class)
            ->weekly()
            ->sundays()
            ->at('22:00')
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command(CleanEventEntriesCommand::class)
            ->dailyAt('00:00')
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command(ProcessWeeklyRankingsCommand::class)
            ->hourly()
            ->runInBackground()
            ->withoutOverlapping();

        $schedule->command('app:cleanup-unverified-users --force')
            ->daily()
            ->at('03:00')
            ->runInBackground()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cleanup-unverified-users.log'));

        $schedule->call(function () {
            try {
                app(GameServerStatusService::class)->updateAllServerStatuses();
            } catch (Exception $e) {
                Log::error("Server status update failed: {$e->getMessage()}");
            }
        })->everyTwoMinutes();

        //        $schedule->command('orders:cleanup')
        //            ->quarterly()
        //            ->runInBackground()
        //            ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
