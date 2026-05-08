<?php

use App\Http\Middleware\EnsureUserIsAdmin;
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'payment/moneroo/webhook',
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('db:backup')->dailyAt('00:00');
        $schedule->command('system:monitor')->hourly();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
