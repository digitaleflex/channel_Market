<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Client;
use Google\Service\Drive;

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
            $client = new Client();
            $credentials = $config['serviceAccountCredentials'] ?? '';
            
            if (!empty($credentials) && file_exists($credentials)) {
                $client->setAuthConfig($credentials);
            }
            
            $client->addScope(Drive::DRIVE);
            
            $adapter = new GoogleDriveAdapter($client, $config['folderId'] ?? '/');
            
            return new Filesystem($adapter);
        });
    }
}
