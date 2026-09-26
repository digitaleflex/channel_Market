<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Variables d'environnement de test à imposer AVANT le bootstrap.
     *
     * Pourquoi : le .env de production est chargé dans $_SERVER, et PHPUnit
     * n'écrit que dans $_ENV + putenv. Or Laravel's env() lit $_SERVER en
     * priorité -> sans cet override, les tests se connectaient a la base de
     * PRODUCTION. Le <env force="true"> de phpunit.xml ne suffit pas.
     */
    private const TEST_ENV = [
        'APP_ENV' => 'testing',
        'APP_CONFIG_CACHE' => '/tmp/phpunit-no-config-cache.php',
        'DB_CONNECTION' => 'sqlite',
        'DB_DATABASE' => ':memory:',
        'CACHE_STORE' => 'array',
        'SESSION_DRIVER' => 'array',
        'MAIL_MAILER' => 'array',
        'QUEUE_CONNECTION' => 'sync',
        'BCRYPT_ROUNDS' => '4',
    ];

    public function createApplication()
    {
        foreach (self::TEST_ENV as $key => $value) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }

        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Garde-fou : les tests ne doivent jamais cibler la production.
        $this->assertSame('sqlite', config('database.default'),
            'Les tests ne doivent jamais cibler la base de production.');
        $this->assertSame(':memory:', config('database.connections.sqlite.database'),
            'Les tests ne doivent jamais cibler la base de production.');

        // Désactiver le CSRF pour les tests
        $this->withoutMiddleware([
            ValidateCsrfToken::class,
        ]);

        // Désactiver Vite pendant les tests
        $this->withoutVite();
    }
}
