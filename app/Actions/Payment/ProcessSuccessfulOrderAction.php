<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessSuccessfulOrderAction
{
    /**
     * Mark an order as paid, send customer confirmation email, and log activity.
     */
    public function execute(Order $order, ?string $transactionId = null): void
    {
        if ($order->status === 'success') {
            return;
        }

        $order->update([
            'status' => 'success',
            'transaction_id' => $transactionId ?? $order->transaction_id,
        ]);

        // Send confirmation email to customer
        try {
            if (! empty($order->client_email)) {
                Mail::to($order->client_email)->queue(new OrderConfirmationMail($order));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to dispatch order confirmation email: '.$e->getMessage(), [
                'order_id' => $order->id,
            ]);
        }

        // Consign activity log
        try {
            ActivityLogger::log(
                'order.paid',
                "Vente confirmée #{$order->id} : {$order->product->title} à {$order->client_name} ({$order->amount} FCFA)",
                $order
            );
        } catch (\Throwable $e) {
            Log::warning('Failed to log order payment activity: '.$e->getMessage(), [
                'order_id' => $order->id,
            ]);
        }
    }
}
