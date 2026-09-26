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
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'payment/moneroo/webhook',
            'payment/chariow/webhook',
            'webhook/deploy',
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Backup unique : Spatie laravel-backup (dump SQL + fichiers)
        // → local + Backblaze B2 (off-site). Les alertes d'échec sont
        // envoyées par BackupHasFailedNotification.
        $schedule->command('backup:run')->dailyAt('02:00');
        $schedule->command('backup:clean')->dailyAt('03:00');
        $schedule->command('system:monitor')->hourly();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
