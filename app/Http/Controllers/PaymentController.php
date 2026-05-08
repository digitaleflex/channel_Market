<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Moneroo\Laravel\Payment as MonerooPayment;

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
     * Initializes Moneroo payment and redirects to Moneroo checkout page.
     */
    public function initMoneroo(Request $request, Product $product)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        // Sanitize phone: keep only digits as Moneroo is strict
        $phone = $validated['phone'] ? preg_replace('/[^0-9]/', '', $validated['phone']) : null;

        $amount = (int) round((float) $product->price);

        $order = Order::create([
            'user_id' => auth()->id(),
            'client_email' => $validated['email'],
            'product_id' => $product->id,
            'amount' => $amount,
            'status' => 'pending',
            'transaction_id' => null,
            'download_token' => (string) Str::uuid(),
        ]);

        try {
            $paymentData = [
                'amount' => $amount,
                'currency' => 'XOF',
                'description' => "Paiement pour la commande #{$order->id}",
                'customer' => [
                    'email' => $validated['email'],
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $phone,
                ],
                'return_url' => route('payment.moneroo.return', ['order' => $order->id]),
                'metadata' => [
                    'order_id' => (string) $order->id,
                    'product_id' => (string) $product->id,
                ],
            ];

            $monerooPayment = new MonerooPayment;
            $payment = $monerooPayment->init($paymentData);

            if (! isset($payment->id, $payment->checkout_url)) {
                Log::warning('Moneroo init: unexpected response', ['payment' => $payment]);

                return redirect()
                    ->route('checkout', $product)
                    ->with('error', "Impossible d'initialiser le paiement. Réessayez.");
            }

            $order->update(['transaction_id' => $payment->id]);

            return redirect()->away($payment->checkout_url);
        } catch (\Throwable $e) {
            Log::error('Moneroo init failed', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('checkout', $product)
                ->with('error', "Erreur lors de l'initialisation du paiement : ".$e->getMessage());
        }
    }

    /**
     * Return URL after Moneroo checkout (always verify via API).
     */
    public function monerooReturn(Request $request, Order $order)
    {
        $paymentId = $request->query('paymentId');
        $paymentStatus = $request->query('paymentStatus');

        if (! $paymentId) {
            return redirect()
                ->route('products.show', $order->product_id)
                ->with('error', 'Retour paiement invalide.');
        }

        // Defensive: ensure the returned payment matches our order
        if ($order->transaction_id && $order->transaction_id !== $paymentId) {
            Log::warning('Moneroo return paymentId mismatch', [
                'order_id' => $order->id,
                'order_transaction_id' => $order->transaction_id,
                'paymentId' => $paymentId,
                'paymentStatus' => $paymentStatus,
            ]);
        }

        try {
            $monerooPayment = new MonerooPayment;
            $payment = $monerooPayment->verify($paymentId);

            $status = $payment->status ?? null;
            $amount = $payment->amount ?? null;

            if ($status === 'success' && (int) $amount >= (int) $order->amount) {
                $order->update([
                    'status' => 'success',
                    'transaction_id' => $paymentId,
                ]);

                if (auth()->check()) {
                    return redirect('/dashboard')
                        ->with('success', 'Paiement réussi ! Votre produit est maintenant disponible dans votre espace.');
                }

                return redirect()
                    ->route('payment.success', $order)
                    ->with('success', 'Paiement réussi, votre téléchargement est prêt.');
            }
        } catch (\Throwable $e) {
            Log::error('Moneroo verify failed', [
                'order_id' => $order->id,
                'paymentId' => $paymentId,
                'message' => $e->getMessage(),
            ]);
        }

        $order->update([
            'status' => 'failed',
            'transaction_id' => $paymentId,
        ]);

        return redirect()
            ->route('products.show', $order->product_id)
            ->with('error', 'Le paiement a échoué ou est incomplet.');
    }

    /**
     * Moneroo webhook endpoint (verify signature + re-query payment status).
     * This is optional but recommended (more reliable than return_url).
     */
    public function monerooWebhook(Request $request)
    {
        $secret = (string) trim(env('MONEROO_WEBHOOK_SECRET', ''));
        $signature = (string) $request->header('X-Moneroo-Signature', '');
        $payload = (string) $request->getContent();

        if ($secret === '' || $signature === '') {
            Log::warning('Moneroo webhook: Missing secret or signature');

            return response()->json(['error' => 'Webhook not configured'], 400);
        }

        $computed = hash_hmac('sha256', $payload, $secret);
        if (! hash_equals($computed, $signature)) {
            Log::error('Moneroo webhook: Invalid signature', [
                'received' => $signature,
                'computed' => $computed,
                'payload_sample' => substr($payload, 0, 100),
            ]);

            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $event = $request->input('event');
        $paymentId = $request->input('data.id');

        if (! $paymentId) {
            return response()->json(['error' => 'Missing payment id'], 422);
        }

        // We always re-query Moneroo to avoid trusting the webhook payload fully.
        try {
            $monerooPayment = new MonerooPayment;
            $payment = $monerooPayment->verify($paymentId);
            $status = $payment->status ?? null;
            $amount = $payment->amount ?? null;
            $metadata = $payment->metadata ?? null;

            $orderId = is_object($metadata) ? ($metadata->order_id ?? null) : null;
            $order = $orderId ? Order::find($orderId) : Order::where('transaction_id', $paymentId)->first();

            if (! $order) {
                Log::warning('Moneroo webhook: order not found', ['paymentId' => $paymentId, 'event' => $event]);

                return response()->json(['ok' => true], 200);
            }

            if ($status === 'success' && (int) $amount >= (int) $order->amount) {
                $order->update(['status' => 'success', 'transaction_id' => $paymentId]);
            } elseif (in_array($status, ['failed', 'cancelled'], true)) {
                $order->update(['status' => 'failed', 'transaction_id' => $paymentId]);
            }

            return response()->json(['ok' => true], 200);
        } catch (\Throwable $e) {
            Log::error('Moneroo webhook verify failed', ['paymentId' => $paymentId, 'message' => $e->getMessage()]);

            return response()->json(['ok' => true], 200);
        }
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
