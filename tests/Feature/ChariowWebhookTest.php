<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ChariowWebhookTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    private Order $order;

    private string $webhookSecret = 'secret_webhook_test_key_123';

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'title' => 'Livre Webhook Test',
            'author' => 'Auteur Webhook',
            'format' => 'PDF',
            'price' => 3000,
            'description' => 'Test webhook',
            'file_path' => 'books/webhook.pdf',
        ]);

        $this->order = Order::create([
            'client_email' => 'buyer@example.com',
            'client_name' => 'Jean Acheteur',
            'client_phone' => '+33612345678',
            'product_id' => $this->product->id,
            'amount' => 3000,
            'status' => 'pending',
            'download_token' => 'test-token-uuid',
        ]);

        Config::set('services.chariow.webhook_secret', $this->webhookSecret);
    }

    public function test_rejects_webhook_when_signature_is_missing_or_invalid(): void
    {
        $payload = [
            'event' => 'successful.sale',
            'sale' => [
                'id' => 'tx_fraud_123',
                'status' => 'completed',
                'custom_metadata' => [
                    'order_id' => (string) $this->order->id,
                ],
            ],
        ];

        // 1. Without signature
        $response = $this->postJson(route('payment.chariow.webhook'), $payload);
        $response->assertStatus(403);
        $this->assertEquals('pending', $this->order->fresh()->status);

        // 2. With invalid signature
        $responseWithBadSig = $this->withHeaders([
            'X-Chariow-Signature' => 'invalid_signature_hash',
        ])->postJson(route('payment.chariow.webhook'), $payload);

        $responseWithBadSig->assertStatus(403);
        $this->assertEquals('pending', $this->order->fresh()->status);
    }

    public function test_updates_order_to_success_with_valid_signature(): void
    {
        $payload = [
            'event' => 'successful.sale',
            'sale' => [
                'id' => 'tx_chariow_valid_999',
                'status' => 'completed',
                'custom_metadata' => [
                    'order_id' => (string) $this->order->id,
                ],
            ],
        ];

        $jsonPayload = json_encode($payload);
        $validSignature = hash_hmac('sha256', $jsonPayload, $this->webhookSecret);

        $response = $this->call(
            'POST',
            route('payment.chariow.webhook'),
            [],
            [],
            [],
            [
                'HTTP_X_CHARIOW_SIGNATURE' => $validSignature,
                'CONTENT_TYPE' => 'application/json',
            ],
            $jsonPayload
        );

        $response->assertStatus(200);

        $freshOrder = $this->order->fresh();
        $this->assertEquals('success', $freshOrder->status);
        $this->assertEquals('tx_chariow_valid_999', $freshOrder->transaction_id);
    }

    public function test_updates_order_to_failed_when_payment_fails(): void
    {
        $payload = [
            'event' => 'failed.sale',
            'sale' => [
                'id' => 'tx_failed_456',
                'status' => 'failed',
                'custom_metadata' => [
                    'order_id' => (string) $this->order->id,
                ],
            ],
        ];

        $jsonPayload = json_encode($payload);
        $validSignature = hash_hmac('sha256', $jsonPayload, $this->webhookSecret);

        $response = $this->call(
            'POST',
            route('payment.chariow.webhook'),
            [],
            [],
            [],
            [
                'HTTP_X_CHARIOW_SIGNATURE' => $validSignature,
                'CONTENT_TYPE' => 'application/json',
            ],
            $jsonPayload
        );

        $response->assertStatus(200);

        $freshOrder = $this->order->fresh();
        $this->assertEquals('failed', $freshOrder->status);
    }
}
