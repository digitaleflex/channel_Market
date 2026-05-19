<?php

namespace App\Providers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::extend('google', function ($app, $config) {
            $client = new Client;
            $credentials = $config['serviceAccountCredentials'] ?? '';

            if (! empty($credentials) && file_exists($credentials)) {
                $client->setAuthConfig($credentials);
            }

            $client->addScope(Drive::DRIVE);

            $service = new Drive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folderId'] ?? '/');

            return new Filesystem($adapter);
        });
    }
}
