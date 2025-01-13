<?php

use App\Http\Middleware\CheckArticlePublishedMiddleware;
use App\Http\Middleware\EnsureVipAccessMiddleware;
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
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'webhook/paypal',
            'webhook/prime',
        ]);

        $middleware->alias([
            'vip.access' => EnsureVipAccessMiddleware::class,
            'article.published' => CheckArticlePublishedMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('orders:expire')->everyMinute();
        $schedule->command('guild:cleanup-marks')->daily();
        $schedule->command('castle:distribute-prizes')->daily();
        //        $schedule->command('orders:cleanup')
        //            ->quarterly()
        //            ->runInBackground()
        //            ->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
