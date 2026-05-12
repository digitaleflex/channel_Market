<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );

        Product::updateOrCreate(
            ['title' => 'E-Book: Master Laravel 11'],
            [
                'description' => 'Un guide complet pour construire des applications web ultra-rapides avec Laravel 11.',
                'price' => 19.99,
                'file_path' => 'downloads/laravel-11-ebook.pdf',
                'image' => 'products/laravel_11_masterclass.png',
            ]
        );

        Product::updateOrCreate(
            ['title' => 'Pack de Scripts Automatisation PHP'],
            [
                'description' => 'Une collection de scripts pour automatiser vos tâches quotidiennes sur serveur Linux.',
                'price' => 49.00,
                'file_path' => 'downloads/php-automation-pack.zip',
                'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=1000&auto=format&fit=crop',
            ]
        );

        Product::updateOrCreate(
            ['title' => 'Template Dashboard Premium'],
            [
                'description' => 'Un template de tableau de bord moderne et responsive avec mode sombre inclus.',
                'price' => 29.50,
                'file_path' => 'downloads/premium-dashboard.zip',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1000&auto=format&fit=crop',
            ]
        );
    }
}
