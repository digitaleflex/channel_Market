<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChariowService
{
    protected string $apiKey = '';
    protected string $apiUrl = '';

    public function __construct()
    {
        $this->apiKey = config('services.chariow.api_key') ?? '';
        $this->apiUrl = rtrim(config('services.chariow.api_url', 'https://api.chariow.com'), '/');
    }

    public function initPayment(array $payload): array
    {
        if (! $this->apiKey) {
            throw new \RuntimeException('Chariow API key is not configured.');
        }

        $response = Http::withHeaders($this->headers())
            ->timeout(15)
            ->post("{$this->apiUrl}/v1/checkout", $payload)
            ->throw()
            ->json();

        return $response['data'] ?? $response;
    }

    public function verifyPayment(string $paymentId): array
    {
        if (! $this->apiKey) {
            throw new \RuntimeException('Chariow API key is not configured.');
        }

        $response = Http::withHeaders($this->headers())
            ->timeout(15)
            ->get("{$this->apiUrl}/v1/checkout/{$paymentId}")
            ->throw()
            ->json();

        return $response['data'] ?? $response;
    }

    public function validateWebhook(Request $request): bool
    {
        if (! $this->webhookSecret) {
            return false;
        }

        $signature = (string) $request->header('X-Chariow-Signature', '');
        if ($signature === '') {
            return false;
        }

        $payload = (string) $request->getContent();
        $computed = hash_hmac('sha256', $payload, $this->webhookSecret);

        return hash_equals($computed, $signature);
    }

    protected function headers(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->apiKey}",
        ];
    }
}
