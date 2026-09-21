<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_catalog_displays_all_books_branding(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('All_Books');
        $response->assertSee('Librairie Digitale');
    }

    public function test_can_search_books_by_author_or_title(): void
    {
        $book1 = Product::create([
            'title' => 'Père Riche Père Pauvre',
            'author' => 'Robert Kiyosaki',
            'category' => 'Finance',
            'format' => 'PDF',
            'price' => 2500,
            'description' => 'Livre de finance culte',
            'file_path' => 'https://example.com/book.pdf',
            'image' => 'https://example.com/cover.jpg',
        ]);

        $book2 = Product::create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'category' => 'Tech',
            'format' => 'EPUB',
            'price' => 4000,
            'description' => 'Guide du bon code',
            'file_path' => 'https://example.com/cleancode.pdf',
            'image' => 'https://example.com/cleancode.jpg',
        ]);

        // Search by author
        $searchResponse = $this->get('/?search=Kiyosaki');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Père Riche Père Pauvre');
        $searchResponse->assertDontSee('Clean Code');

        // Filter by category
        $categoryResponse = $this->get('/?category=Tech');
        $categoryResponse->assertStatus(200);
        $categoryResponse->assertSee('Clean Code');
        $categoryResponse->assertDontSee('Père Riche Père Pauvre');
    }

    public function test_book_detail_page_renders_specs_and_author(): void
    {
        $book = Product::create([
            'title' => 'L\'Effet Cumulé',
            'author' => 'Darren Hardy',
            'category' => 'Développement Personnel',
            'format' => 'PDF + EPUB',
            'pages_count' => 240,
            'language' => 'Français',
            'publication_year' => 2024,
            'isbn' => '978-2-89225-871-3',
            'price' => 2000,
            'description' => 'De petits changements quotidiens pour des résultats monumentaux.',
            'file_path' => 'https://example.com/effet-cumule.pdf',
            'image' => 'https://example.com/cover.jpg',
        ]);

        $response = $this->get(route('products.show', $book));

        $response->assertStatus(200);
        $response->assertSee('L\'Effet Cumulé');
        $response->assertSee('Darren Hardy');
        $response->assertSee('240 pages');
        $response->assertSee('978-2-89225-871-3');
        $response->assertSee('PDF + EPUB');
    }

    public function test_book_sample_redirects_if_external_url(): void
    {
        $book = Product::create([
            'title' => 'Livre avec Extrait',
            'author' => 'Auteur Test',
            'format' => 'PDF',
            'price' => 1000,
            'description' => 'Description test',
            'file_path' => 'https://example.com/full.pdf',
            'sample_file' => 'https://example.com/sample.pdf',
            'image' => 'https://example.com/cover.jpg',
        ]);

        $response = $this->get(route('products.sample', $book));

        $response->assertRedirect('https://example.com/sample.pdf');
    }
}
