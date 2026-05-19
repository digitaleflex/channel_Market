<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$chariow = app(\App\Services\ChariowService::class);
try {
    $chariow->initPayment([
        'amount' => 5000,
        'currency' => 'XOF',
        'description' => 'Test',
        'email' => 'test@test.com',
        'first_name' => 'test',
        'last_name' => 'test',
        'phone' => ['number' => '12345678', 'country_code' => 'FR'],
        'redirect_url' => 'http://localhost',
        'product_id' => 'prod_xyz',
        'custom_metadata' => [
            'order_id' => '123',
            'product_id' => '456',
        ],
    ]);
    echo "Success!\n";
} catch (\Illuminate\Http\Client\RequestException $e) {
    echo json_encode($e->response->json(), JSON_PRETTY_PRINT);
}
