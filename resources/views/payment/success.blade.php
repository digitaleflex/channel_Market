@extends('layouts.public')
@section('title', 'Paiement réussi')

@section('content')
@push('tracking-events')
    <script>
        if (typeof fbq === 'function') {
            fbq('track', 'Purchase', {
                content_name: '{{ $order->product->title }}',
                content_ids: ['{{ $order->product->id }}'],
                content_type: 'product',
                value: {{ $order->amount }},
                currency: 'XOF',
                order_id: '{{ $order->id }}'
            });
        }
        if (typeof gtag === 'function') {
            gtag('event', 'purchase', {
                transaction_id: '{{ $order->id }}',
                value: {{ $order->amount }},
                currency: 'XOF',
                items: [{
                    item_id: '{{ $order->product->id }}',
                    item_name: '{{ $order->product->title }}',
                    price: {{ $order->amount }},
                    currency: 'XOF'
                }]
            });
        }
    </script>
@endpush
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-gray-100 text-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full blur-2xl opacity-60"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-gradient-to-tr from-amber-100 to-orange-100 rounded-full blur-2xl opacity-50"></div>

        <div class="relative z-10">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-green-600 flex items-center justify-center shadow-lg shadow-green-600/30 mb-6">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h2 class="text-3xl font-extrabold text-gray-900 mb-2 font-['Outfit']">Paiement confirmé</h2>
            <p class="text-gray-500 mb-10">Votre produit est prêt. Cliquez ci-dessous pour télécharger.</p>

            @if($order->product)
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 mb-10 inline-block text-left w-full max-w-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <p class="text-sm text-gray-500">Produit</p>
                            <p class="text-lg font-bold text-gray-900">{{ $order->product->title }}</p>
                        </div>
                        <div class="text-sm text-gray-500">
                            Commande #{{ $order->id }}
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ URL::temporarySignedRoute('download', now()->addHours(48), ['token' => $order->download_token]) }}" target="_blank" class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 bg-amber-600 text-white font-bold rounded-2xl hover:bg-amber-700 transition shadow-lg shadow-amber-500/30 transform hover:-translate-y-1">
                @if(filter_var($order->product->file_path, FILTER_VALIDATE_URL))
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Accéder au lien du produit
                @else
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Télécharger mon fichier (PDF)
                @endif
            </a>

            <p class="text-xs text-gray-400 mt-6">
                Conservez ce lien si vous souhaitez retélécharger plus tard.
            </p>
        </div>
    </div>
</div>
@endsection

