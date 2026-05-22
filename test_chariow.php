<?php

use App\Services\ChariowService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Client\RequestException;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$chariow = app(ChariowService::class);
try {
    $chariow->initPayment([
        'amount' => 5000,
        'currency' => 'XOF',
        'description' => 'Test',
        'customer' => [
            'email' => 'test@test.com',
            'first_name' => 'test',
            'last_name' => 'test',
            'phone' => ['number' => '12345678', 'country_code' => 'FR'],
        ],
        'redirect_url' => 'http://localhost',
        'custom_metadata' => [
            'order_id' => '123',
            'product_id' => '456',
        ],
    ]);
} catch (RequestException $e) {
    echo json_encode($e->response->json(), JSON_PRETTY_PRINT);
}
