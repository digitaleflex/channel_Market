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
        'amount' => 2000,
        'currency' => 'XOF',
        'description' => 'Paiement pour la commande #1',
        'email' => 'digitaleflex@gmail.com',
        'first_name' => 'Eflex',
        'last_name' => 'Digital',
        'phone' => ['number' => '612345678', 'country_code' => 'FR'],
        'redirect_url' => 'https://channelmarket.net/payment/chariow/return/550e8400-e29b-41d4-a716-446655440000?sale={sale_id}',
        'product_id' => 'prd_4x46xh',
        'custom_metadata' => [
            'order_id' => '1',
            'product_id' => '14',
        ],
    ]);
    echo "Success!\n";
} catch (RequestException $e) {
    echo json_encode($e->response->json(), JSON_PRETTY_PRINT);
}
