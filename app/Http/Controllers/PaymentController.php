<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\chariowService;
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
    public function init(Request $request, Product $product, chariowService $chariow)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
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
            $redirectUrl = route('payment.chariow.return', ['order' => $order->id]).'?sale='.urlencode('{sale_id}');

            $paymentData = [
                'amount' => $amount,
                'currency' => $product->currency ?? 'XOF',
                'description' => "Paiement pour la commande #{$order->id}",
                'customer' => [
                    'email' => $validated['email'],
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $phone,
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

                \App\Services\ActivityLogger::log('order_success', "Nouvelle vente de {$order->amount} CFA pour le produit : {$order->product->title}", $order);

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
        $saleId = $request->query('sale');

        if (! $saleId) {
            return redirect()
                ->route('products.show', $order->product_id)
                ->with('error', 'Retour paiement invalide.');
        }

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
    public function chariowWebhook(Request $request, chariowService $chariow)
    {
        if (! $chariow->validateWebhook($request)) {
            Log::error('chariow webhook: invalid signature');

            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $payload = $request->json()->all();
        $paymentId = $payload['data']['id'] ?? $payload['id'] ?? null;
        $status = strtolower($payload['data']['status'] ?? $payload['status'] ?? '');
        $metadata = $payload['data']['custom_metadata'] ?? $payload['custom_metadata'] ?? [];
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
            if ($order->status !== 'success') {
                $order->update(['status' => 'success', 'transaction_id' => $paymentId]);
                \App\Services\ActivityLogger::log('order_success', "Nouvelle vente de {$order->amount} CFA pour le produit : {$order->product->title}", $order);
            }
        } elseif (in_array($status, ['failed', 'cancelled', 'refused', 'expired'], true)) {
            $order->update(['status' => 'failed', 'transaction_id' => $paymentId]);
        }

        return response()->json(['ok' => true], 200);
    }

    private function normalizePhone(string $phone): array
    {
        $digits = preg_replace('/[^0-9]+/', '', $phone);
        $countryCode = config('services.chariow.default_country_code', 'FR');

        if (str_starts_with(trim($phone), '+')) {
            if (preg_match('/^\+([0-9]{1,3})/', trim($phone), $matches)) {
                $countryCode = $this->countryCodeFromDialCode($matches[1]) ?? $countryCode;
            }
        }

        return [
            'number' => $digits,
            'country_code' => $countryCode,
        ];
    }

    private function countryCodeFromDialCode(string $dialCode): ?string
    {
        $map = [
            '33' => 'FR',
            '229' => 'BJ',
            '225' => 'CI',
            '237' => 'CM',
            '243' => 'CD',
            '226' => 'BF',
            '228' => 'TG',
            '236' => 'CF',
            '241' => 'GA',
        ];

        return $map[$dialCode] ?? null;
    }

    private function resolveChariowProductId(Product $product): string
    {
        return $product->chariow_product_id ?? (string) $product->id;
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
