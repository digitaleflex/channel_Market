<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Secure download via signed URL.
     */
    public function downloadByToken(Request $request, string $token)
    {
        // 1. Verify the signature (Pentest best practice)
        if (! $request->hasValidSignature()) {
            abort(403, 'Ce lien de téléchargement a expiré ou est invalide.');
        }

        $order = Order::where('download_token', $token)
            ->where('status', 'success')
            ->firstOrFail();

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
