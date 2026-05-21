<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\ChariowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Checkout page (collect customer info, then init payment).
     */
    public function checkout(Product $product)
    {
        return view('payment.checkout', compact('product'));
    }

    /**
     * Initializes chariow payment and redirects to the chariow checkout page.
     */
    public function init(Request $request, Product $product, ChariowService $chariow)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+[0-9 ]{6,25}$/'],
        ]);

        $amount = (int) round((float) $product->price);
        $phone = $this->normalizePhone($validated['phone']);

        $order = Order::create([
            'user_id' => auth()->id(),
            'client_email' => $validated['email'],
            'client_name' => $validated['first_name'].' '.$validated['last_name'],
            'client_phone' => $validated['phone'],
            'product_id' => $product->id,
            'amount' => $amount,
            'status' => 'pending',
            'transaction_id' => null,
            'download_token' => (string) Str::uuid(),
        ]);

        try {
            $productId = $this->resolveChariowProductId($product);
            $redirectUrl = rtrim(config('app.url'), '/').route('payment.chariow.return', ['order' => $order->id], false);

            $paymentData = [
                'product_id' => $productId,
                'email' => $validated['email'],
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => [
                    'number' => $phone['number'],
                    'country_code' => $phone['country_code'],
                ],
                'redirect_url' => $redirectUrl,
                'custom_metadata' => [
                    'order_id' => (string) $order->id,
                    'product_id' => (string) $product->id,
                ],
            ];

            $checkout = $chariow->initPayment($paymentData);
            $step = $checkout['step'] ?? null;
            $purchase = $checkout['purchase'] ?? [];
            $payment = $checkout['payment'] ?? [];
            $paymentUrl = $payment['checkout_url'] ?? null;
            $transactionId = $payment['transaction_id'] ?? $purchase['id'] ?? null;

            if ($step === 'payment' && $paymentUrl) {
                $order->update(['transaction_id' => $transactionId]);

                return redirect()->away($paymentUrl);
            }

            if ($step === 'completed') {
                $order->update([
                    'status' => 'success',
                    'transaction_id' => $transactionId,
                ]);

                if (auth()->check()) {
                    return redirect('/dashboard')
                        ->with('success', 'Paiement réussi ! Votre produit est maintenant disponible dans votre espace.');
                }

                return redirect()->route('payment.success', $order)
                    ->with('success', 'Paiement réussi, votre téléchargement est prêt.');
            }

            if ($step === 'already_purchased') {
                return redirect()
                    ->route('products.show', $product)
                    ->with('error', 'Vous avez déjà acheté ce produit.');
            }

            Log::warning('chariow init: unexpected response', ['checkout' => $checkout]);

            return redirect()
                ->route('checkout', $product)
                ->with('error', "Impossible d'initialiser le paiement. Réessayez.");
        } catch (\Throwable $e) {
            Log::error('chariow init failed', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('checkout', $product)
                ->with('error', "Erreur lors de l'initialisation du paiement : ".$e->getMessage());
        }
    }

    /**
     * Return URL after chariow checkout.
     */
    public function chariowReturn(Request $request, Order $order)
    {
        if ($order->status === 'success') {
            if (auth()->check()) {
                return redirect('/dashboard')
                    ->with('success', 'Paiement réussi ! Votre produit est maintenant disponible dans votre espace.');
            }

            return redirect()->route('payment.success', $order)
                ->with('success', 'Paiement réussi, votre téléchargement est prêt.');
        }

        return redirect()
            ->route('products.show', $order->product_id)
            ->with('success', 'Votre paiement est en cours de confirmation. Vous recevrez l’accès dès que la transaction sera validée.');
    }

    /**
     * chariow webhook endpoint.
     */
    public function chariowWebhook(Request $request, ChariowService $chariow)
    {
        $payload = $request->json()->all();
        $event = $payload['event'] ?? '';

        if ($event === 'successful.sale' || $event === 'completed') {
            $sale = $payload['sale'] ?? [];
            $paymentId = $sale['id'] ?? null;
            $status = strtolower($sale['status'] ?? '');
            $metadata = $sale['custom_metadata'] ?? [];
            $orderId = $metadata['order_id'] ?? null;

            if (! $paymentId) {
                return response()->json(['error' => 'Missing payment id'], 422);
            }

            $order = $orderId ? Order::find($orderId) : Order::where('transaction_id', $paymentId)->first();

            if (! $order) {
                Log::warning('chariow webhook: order not found', ['paymentId' => $paymentId]);

                return response()->json(['ok' => true], 200);
            }

            if (in_array($status, ['success', 'paid', 'approved', 'completed'], true)) {
                $order->update(['status' => 'success', 'transaction_id' => $paymentId]);
            }
        } elseif ($event === 'failed.sale' || $event === 'abandoned.sale') {
            $sale = $payload['sale'] ?? [];
            $paymentId = $sale['id'] ?? null;
            $metadata = $sale['custom_metadata'] ?? [];
            $orderId = $metadata['order_id'] ?? null;

            $order = $orderId ? Order::find($orderId) : Order::where('transaction_id', $paymentId)->first();
            if ($order) {
                $order->update(['status' => 'failed', 'transaction_id' => $paymentId]);
            }
        }

        return response()->json(['ok' => true], 200);
    }

    private function normalizePhone(string $phone): array
    {
        $trimmed = trim($phone);
        $digits = preg_replace('/[^0-9]+/', '', $trimmed);
        $countryCode = config('services.chariow.default_country_code', 'FR');
        $nationalNumber = $digits;

        if (str_starts_with($trimmed, '+')) {
            if (preg_match('/^\+([0-9]{1,3})/', $trimmed, $matches)) {
                $dialCode = $matches[1];
                $countryCode = $this->countryCodeFromDialCode($dialCode) ?? $countryCode;
                $nationalNumber = preg_replace('/^'.preg_quote($dialCode, '/').'/', '', $digits);
            }
        }

        return [
            'number' => $nationalNumber,
            'country_code' => $countryCode,
        ];
    }

    private function countryCodeFromDialCode(string $dialCode): ?string
    {
        $map = [
            '20' => 'EG',
            '211' => 'SS',
            '212' => 'MA',
            '213' => 'DZ',
            '216' => 'TN',
            '218' => 'LY',
            '220' => 'GM',
            '221' => 'SN',
            '222' => 'MR',
            '223' => 'ML',
            '224' => 'GN',
            '225' => 'CI',
            '226' => 'BF',
            '227' => 'NE',
            '228' => 'TG',
            '229' => 'BJ',
            '230' => 'MU',
            '231' => 'LR',
            '232' => 'SL',
            '233' => 'GH',
            '234' => 'NG',
            '235' => 'TD',
            '236' => 'CF',
            '237' => 'CM',
            '238' => 'CV',
            '239' => 'ST',
            '240' => 'GQ',
            '241' => 'GA',
            '242' => 'CG',
            '243' => 'CD',
            '244' => 'AO',
            '245' => 'GW',
            '246' => 'IO',
            '248' => 'SC',
            '249' => 'SD',
            '250' => 'RW',
            '251' => 'ET',
            '252' => 'SO',
            '253' => 'DJ',
            '254' => 'KE',
            '255' => 'TZ',
            '256' => 'UG',
            '257' => 'BI',
            '258' => 'MZ',
            '260' => 'ZM',
            '261' => 'MG',
            '262' => 'RE',
            '263' => 'ZW',
            '264' => 'NA',
            '265' => 'MW',
            '266' => 'LS',
            '267' => 'BW',
            '268' => 'SZ',
            '269' => 'KM',
            '27' => 'ZA',
            '290' => 'SH',
            '291' => 'ER',
        ];

        return $map[$dialCode] ?? null;
    }

    private function resolveChariowProductId(Product $product): string
    {
        return $product->chariow_product_id ?: config('services.chariow.generic_product_id', (string) $product->id);
    }

    /**
     * Public success page (download by token).
     */
    public function success(Order $order)
    {
        if ($order->status !== 'success') {
            return redirect()
                ->route('products.show', $order->product_id)
                ->with('error', 'Cette commande n’est pas payée.');
        }

        return view('payment.success', compact('order'));
    }
}
