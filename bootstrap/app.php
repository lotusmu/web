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
        $middleware->web(CheckUserBannedMiddleware::class);

        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'webhook/paypal',
            'webhook/prime',
        ]);

        $middleware->alias([
            'vip.only' => EnsureVipOnlyMiddleware::class,
            'non.vip.only' => EnsureNonVipOnlyMiddleware::class,
            'article.published' => CheckArticlePublishedMiddleware::class,
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
            ->fridays()
            ->at('18:23')
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
        //        $schedule->command('orders:cleanup')
        //            ->quarterly()
        //            ->runInBackground()
        //            ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
