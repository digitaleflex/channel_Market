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
                ->with('error', "Erreur lors de l'initialisation du paiement : " . $e->getMessage());
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
                $nationalNumber = preg_replace('/^' . preg_quote($dialCode, '/') . '/', '', $digits);
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
            '1' => 'US',
            '7' => 'RU',
            '20' => 'EG',
            '27' => 'ZA',
            '30' => 'GR',
            '31' => 'NL',
            '32' => 'BE',
            '33' => 'FR',
            '34' => 'ES',
            '36' => 'HU',
            '39' => 'IT',
            '40' => 'RO',
            '41' => 'CH',
            '43' => 'AT',
            '44' => 'GB',
            '45' => 'DK',
            '46' => 'SE',
            '47' => 'NO',
            '48' => 'PL',
            '49' => 'DE',
            '51' => 'PE',
            '52' => 'MX',
            '53' => 'CU',
            '54' => 'AR',
            '55' => 'BR',
            '56' => 'CL',
            '57' => 'CO',
            '58' => 'VE',
            '60' => 'MY',
            '61' => 'AU',
            '62' => 'ID',
            '63' => 'PH',
            '64' => 'NZ',
            '65' => 'SG',
            '66' => 'TH',
            '81' => 'JP',
            '82' => 'KR',
            '84' => 'VN',
            '86' => 'CN',
            '90' => 'TR',
            '91' => 'IN',
            '92' => 'PK',
            '93' => 'AF',
            '94' => 'LK',
            '95' => 'MM',
            '98' => 'IR',
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
            '290' => 'SH',
            '291' => 'ER',
            '297' => 'AW',
            '298' => 'FO',
            '299' => 'GL',
            '350' => 'GI',
            '351' => 'PT',
            '352' => 'LU',
            '353' => 'IE',
            '354' => 'IS',
            '355' => 'AL',
            '356' => 'MT',
            '357' => 'CY',
            '358' => 'FI',
            '359' => 'BG',
            '370' => 'LT',
            '371' => 'LV',
            '372' => 'EE',
            '373' => 'MD',
            '374' => 'AM',
            '375' => 'BY',
            '376' => 'AD',
            '377' => 'MC',
            '378' => 'SM',
            '379' => 'VA',
            '380' => 'UA',
            '381' => 'RS',
            '382' => 'ME',
            '383' => 'XK',
            '385' => 'HR',
            '386' => 'SI',
            '387' => 'BA',
            '389' => 'MK',
            '420' => 'CZ',
            '421' => 'SK',
            '423' => 'LI',
            '500' => 'FK',
            '501' => 'BZ',
            '502' => 'GT',
            '503' => 'SV',
            '504' => 'HN',
            '505' => 'NI',
            '506' => 'CR',
            '507' => 'PA',
            '508' => 'PM',
            '509' => 'HT',
            '590' => 'GP',
            '591' => 'BO',
            '592' => 'GY',
            '593' => 'EC',
            '594' => 'GF',
            '595' => 'PY',
            '596' => 'MQ',
            '597' => 'SR',
            '598' => 'UY',
            '670' => 'TL',
            '672' => 'AQ',
            '673' => 'BN',
            '674' => 'NR',
            '675' => 'PG',
            '676' => 'TO',
            '677' => 'SB',
            '678' => 'VU',
            '679' => 'FJ',
            '680' => 'PW',
            '681' => 'WF',
            '682' => 'CK',
            '683' => 'NU',
            '685' => 'WS',
            '686' => 'KI',
            '687' => 'NC',
            '688' => 'TV',
            '689' => 'PF',
            '690' => 'TK',
            '691' => 'FM',
            '692' => 'MH',
            '850' => 'KP',
            '852' => 'HK',
            '853' => 'MO',
            '855' => 'KH',
            '856' => 'LA',
            '880' => 'BD',
            '886' => 'TW',
            '971' => 'AE',
            '972' => 'IL',
            '973' => 'BH',
            '974' => 'QA',
            '975' => 'BT',
            '976' => 'MN',
            '977' => 'NP',
            '992' => 'TJ',
            '993' => 'TM',
            '994' => 'AZ',
            '995' => 'GE',
            '996' => 'KG',
            '998' => 'UZ',
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
