@extends('layouts.public')
@section('title', 'Paiement Sécurisé')
@section('content')
@push('tracking-events')
    <script>
        if (typeof fbq === 'function') {
            fbq('track', 'InitiateCheckout', {
                content_name: '{{ $product->title }}',
                content_ids: ['{{ $product->id }}'],
                content_type: 'product',
                currency: '{{ $product->currency ?? 'XOF' }}'
            });
        }
        if (typeof gtag === 'function') {
            gtag('event', 'begin_checkout', {
                items: [{
                    item_id: '{{ $product->id }}',
                    item_name: '{{ $product->title }}',
                    price: {{ $product->price }},
                    currency: '{{ $product->currency ?? 'XOF' }}'
                }]
            });
        }
    </script>
@endpush
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-gray-100 text-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full blur-2xl opacity-50"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-gradient-to-tr from-green-100 to-teal-100 rounded-full blur-2xl opacity-50"></div>

        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2 font-['Outfit']">Finaliser votre achat</h2>
            <p class="text-gray-500 mb-10">Vous êtes sur le point d'acquérir "{{ $product->title }}"</p>

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 mb-10 inline-block text-left w-full max-w-sm">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-600 font-medium">Prix unitaire</span>
                    <span class="text-gray-900 font-bold">{{ number_format($product->price, $product->currency === 'XOF' ? 0 : 2, ',', ' ') }} {{ $product->currency === 'XOF' ? 'CFA' : $product->currency }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-600 font-medium">Frais de traitement</span>
                    <span class="text-green-600 font-bold">0,00 {{ $product->currency === 'XOF' ? 'CFA' : $product->currency }}</span>
                </div>
                <div class="w-full h-px bg-gray-200 mb-4"></div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-900 font-bold text-lg">Total à payer</span>
                    <span class="text-amber-600 font-black text-2xl">{{ number_format($product->price, $product->currency === 'XOF' ? 0 : 2, '', '') }} {{ $product->currency === 'XOF' ? 'CFA' : $product->currency }}</span>
                </div>
            </div>

            @if(session('error'))
                <div class="max-w-xl mx-auto mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm text-left">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.init', $product) }}" class="max-w-xl mx-auto text-left">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Prénom</label>
                        <input name="first_name" value="{{ old('first_name') }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500" />
                        @error('first_name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                        <input name="last_name" value="{{ old('last_name') }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500" />
                        @error('last_name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500" />
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Numéro WhatsApp (Obligatoire)</label>
                    <input name="phone" value="{{ old('phone') }}" required placeholder="Ex: +229 01020304"
                           class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500" />
                    @error('phone')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="inline-flex items-center justify-center w-full px-6 py-3 bg-amber-600 text-white font-semibold rounded-2xl hover:bg-amber-700 transition shadow-md hover:shadow-lg">
                        Payer
                    </button>
                </div>
            </form>

            <p class="text-xs text-gray-400 mt-6 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Paiement 100% sécurisé
            </p>
        </div>
    </div>
</div>
@endsection
