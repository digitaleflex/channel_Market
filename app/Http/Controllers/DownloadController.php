<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Download the digital product if order is paid (token-based).
     */
    public function downloadByToken(string $token)
    {
        $order = Order::where('download_token', $token)->firstOrFail();

        // Security logic: check if order is paid
        if ($order->status !== 'success') {
            abort(403, 'Paiement non valide ou en attente');
        }

        // Expiration logic: link expires after 48 hours
        $expirationHours = 48;
        if ($order->updated_at->addHours($expirationHours)->isPast()) {
            abort(410, 'Ce lien de téléchargement a expiré (limite de 48h).');
        }

        $product = $order->product;

        // Handle external links (like Google Drive)
        if (filter_var($product->file_path, FILTER_VALIDATE_URL)) {
            return redirect()->away($product->file_path);
        }

        if (! $product || ! Storage::exists($product->file_path)) {
            abort(404, 'Fichier indisponible');
        }

        return Storage::download($product->file_path);
    }
}
