<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SecureDownloadTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'title' => 'Livre Téléchargement Sécurisé',
            'author' => 'Auteur Sécurité',
            'format' => 'PDF',
            'price' => 4500,
            'description' => 'Test téléchargement sécurisé',
            'file_path' => 'https://example.com/external-book-drive.pdf',
        ]);

        $this->order = Order::create([
            'client_email' => 'reader@example.com',
            'client_name' => 'Lecteur Test',
            'client_phone' => '+22997000000',
            'product_id' => $this->product->id,
            'amount' => 4500,
            'status' => 'success',
            'download_token' => 'secure-token-12345',
        ]);
    }

    public function test_rejects_download_without_valid_signed_signature(): void
    {
        // Unsigned request
        $response = $this->get('/download/'.$this->order->download_token);

        $response->assertStatus(403);
    }

    public function test_allows_download_with_valid_signed_url(): void
    {
        $signedUrl = URL::signedRoute('download', ['token' => $this->order->download_token]);

        $response = $this->get($signedUrl);

        // Product file_path is external URL so it redirects away
        $response->assertRedirect('https://example.com/external-book-drive.pdf');
    }

    public function test_rejects_download_if_order_is_older_than_48_hours(): void
    {
        // Simulate order completed 50 hours ago
        $this->order->updated_at = Carbon::now()->subHours(50);
        $this->order->save();

        $signedUrl = URL::signedRoute('download', ['token' => $this->order->download_token]);

        $response = $this->get($signedUrl);

        $response->assertStatus(410);
    }
}
