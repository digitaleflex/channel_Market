<?php

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

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        Product::create([
            'title' => 'E-Book: Master Laravel 11',
            'description' => 'A complete guide to building blazing fast web apps with Laravel 11.',
            'price' => 19.99,
            'file_path' => 'downloads/laravel-11-ebook.pdf',
            'image' => 'https://via.placeholder.com/400x300.png?text=Laravel+E-Book',
        ]);
    }
}
