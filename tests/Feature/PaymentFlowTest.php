<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'title' => 'Livre Test E-commerce',
            'author' => 'Auteur Exemple',
            'category' => 'Business',
            'format' => 'PDF',
            'price' => 5000,
            'description' => 'Un livre de test pour le paiement',
            'file_path' => 'books/test.pdf',
            'chariow_product_id' => 'prod_chariow_123',
        ]);

        Config::set('services.chariow.api_key', 'test_chariow_key');
        Config::set('services.chariow.api_url', 'https://api.chariow.com');
        Config::set('services.chariow.webhook_secret', 'test_secret');
    }

    public function test_checkout_page_renders_product_details(): void
    {
        $response = $this->get(route('checkout', $this->product));

        $response->assertStatus(200);
        $response->assertSee('Livre Test E-commerce');
        $response->assertSee('5 000');
    }

    public function test_checkout_validation_rejects_invalid_phone(): void
    {
        $response = $this->post(route('checkout.init', $this->product), [
            'email' => 'client@example.com',
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'phone' => 'invalid-phone-without-plus',
        ]);

        $response->assertSessionHasErrors(['phone']);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_initialization_redirects_to_chariow_url(): void
    {
        Http::fake([
            'https://api.chariow.com/v1/checkout' => Http::response([
                'step' => 'payment',
                'payment' => [
                    'checkout_url' => 'https://checkout.chariow.com/pay/ch_test_999',
                    'transaction_id' => 'tx_999',
                ],
            ], 200),
        ]);

        $response = $this->post(route('checkout.init', $this->product), [
            'email' => 'client@example.com',
            'first_name' => 'Melvine',
            'last_name' => 'Eyemadje',
            'phone' => '+229 97 00 00 00',
        ]);

        $response->assertRedirect('https://checkout.chariow.com/pay/ch_test_999');

        $this->assertDatabaseHas('orders', [
            'client_email' => 'client@example.com',
            'client_name' => 'Melvine Eyemadje',
            'product_id' => $this->product->id,
            'amount' => 5000,
            'status' => 'pending',
            'transaction_id' => 'tx_999',
        ]);

        $order = Order::first();
        $this->assertNotNull($order->download_token);
    }
}
