<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Payment\ProcessSuccessfulOrderAction;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\ChariowService;
use App\Services\PhoneNormalizerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display the checkout page for a digital book.
     */
    public function checkout(Product $product): View
    {
        return view('payment.checkout', compact('product'));
    }

    /**
     * Initializes Chariow payment and redirects to the checkout URL.
     */
    public function init(
        CheckoutRequest $request,
        Product $product,
        ChariowService $chariow,
        PhoneNormalizerService $phoneNormalizer,
        ProcessSuccessfulOrderAction $processSuccessfulOrder
    ): RedirectResponse {
        $validated = $request->validated();
        $amount = (int) round((float) $product->price);
        $phone = $phoneNormalizer->normalize($validated['phone']);

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
            $redirectUrl = rtrim((string) config('app.url'), '/').route('payment.chariow.return', ['order' => $order->id], false);

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
                $processSuccessfulOrder->execute($order, $transactionId);

                if (auth()->check()) {
                    return redirect('/dashboard')
                        ->with('success', 'Paiement réussi ! Votre livre est maintenant disponible dans votre bibliothèque.');
                }

                return redirect()->route('payment.success', $order)
                    ->with('success', 'Paiement réussi, votre téléchargement est prêt.');
            }

            if ($step === 'already_purchased') {
                return redirect()
                    ->route('products.show', $product)
                    ->with('error', 'Vous avez déjà acquis ce produit.');
            }

            Log::warning('Chariow init: unexpected step response', ['checkout' => $checkout]);

            return redirect()
                ->route('checkout', $product)
                ->with('error', 'Impossible d\'initialiser le paiement. Veuillez réessayer.');
        } catch (\Throwable $e) {
            Log::error('Chariow init failed', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('checkout', $product)
                ->with('error', 'Erreur lors de l\'initialisation du paiement : '.$e->getMessage());
        }
    }

    /**
     * Return destination after Chariow payment redirection.
     */
    public function chariowReturn(Request $request, Order $order): RedirectResponse
    {
        if ($order->status === 'success') {
            if (auth()->check()) {
                return redirect('/dashboard')
                    ->with('success', 'Paiement confirmé ! Votre ouvrage est accessible dans votre bibliothèque.');
            }

            return redirect()->route('payment.success', $order)
                ->with('success', 'Paiement confirmé, votre téléchargement est prêt.');
        }

        return redirect()
            ->route('products.show', $order->product_id)
            ->with('success', 'Votre paiement est en cours de confirmation par l\'opérateur. Vous recevrez l\'accès dès validation.');
    }

    /**
     * Chariow Webhook endpoint with HMAC signature verification.
     */
    public function chariowWebhook(
        Request $request,
        ChariowService $chariow,
        ProcessSuccessfulOrderAction $processSuccessfulOrder
    ): JsonResponse {
        // Enforce cryptographic HMAC SHA-256 signature if webhook secret is configured
        if (config('services.chariow.webhook_secret') && ! $chariow->validateWebhook($request)) {
            Log::warning('Chariow webhook: signature mismatch or unverified payload', [
                'ip' => $request->ip(),
            ]);

            return response()->json(['error' => 'Invalid webhook signature'], 403);
        }

        $payload = $request->json()->all();
        $event = $payload['event'] ?? '';

        if ($event === 'successful.sale' || $event === 'completed') {
            $sale = $payload['sale'] ?? [];
            $paymentId = $sale['id'] ?? null;
            $status = strtolower($sale['status'] ?? '');
            $metadata = $sale['custom_metadata'] ?? [];
            $orderId = $metadata['order_id'] ?? null;

            if (! $paymentId) {
                return response()->json(['error' => 'Missing payment ID'], 422);
            }

            $order = $orderId ? Order::find($orderId) : Order::where('transaction_id', $paymentId)->first();

            if (! $order) {
                Log::warning('Chariow webhook: order not found', ['paymentId' => $paymentId]);

                return response()->json(['ok' => true], 200);
            }

            if (in_array($status, ['success', 'paid', 'approved', 'completed'], true)) {
                $processSuccessfulOrder->execute($order, (string) $paymentId);
            }
        } elseif ($event === 'failed.sale' || $event === 'abandoned.sale') {
            $sale = $payload['sale'] ?? [];
            $paymentId = $sale['id'] ?? null;
            $metadata = $sale['custom_metadata'] ?? [];
            $orderId = $metadata['order_id'] ?? null;

            $order = $orderId ? Order::find($orderId) : Order::where('transaction_id', $paymentId)->first();
            if ($order && $order->status !== 'success') {
                $order->update([
                    'status' => 'failed',
                    'transaction_id' => $paymentId,
                ]);
            }
        }

        return response()->json(['ok' => true], 200);
    }

    /**
     * Public success page (download via signed link).
     */
    public function success(Order $order): View|RedirectResponse
    {
        if ($order->status !== 'success') {
            return redirect()
                ->route('products.show', $order->product_id)
                ->with('error', 'Cette commande n’est pas encore validée.');
        }

        return view('payment.success', compact('order'));
    }

    /**
     * Resolve the product identifier for Chariow.
     */
    private function resolveChariowProductId(Product $product): string
    {
        return $product->chariow_product_id ?: (string) config('services.chariow.generic_product_id', (string) $product->id);
    }
}
