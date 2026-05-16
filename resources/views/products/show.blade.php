@extends('layouts.public')

@php
    $title = $product->title;
    $description = Str::limit(strip_tags($product->description), 160);
    $ogImage = filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image);
@endphp

@section('content')
@push('tracking-events')
    <script>
        if (typeof fbq === 'function') {
            fbq('track', 'ViewContent', {
                content_name: '{{ $product->title }}',
                content_ids: ['{{ $product->id }}'],
                content_type: 'product',
                currency: '{{ $product->currency ?? 'XOF' }}'
            });
        }
        if (typeof gtag === 'function') {
            gtag('event', 'view_item', {
                items: [{
                    item_id: '{{ $product->id }}',
                    item_name: '{{ $product->title }}',
                    price: {{ $product->price }},
                    currency: '{{ $product->currency ?? 'XOF' }}'
                }]
            });
        }
    </script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@@type": "Product",
      "name": "{{ $product->title }}",
      "image": "{{ $product->image }}",
      "description": "{{ Str::limit(strip_tags($product->description), 160) }}",
      "offers": {
        "@@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "{{ $product->currency ?? 'XOF' }}",
        "price": "{{ $product->price }}",
        "availability": "https://schema.org/InStock"
      }
    }
    </script>
@endpush

<div class="container-app py-12 md:py-20">
    <div class="mb-12">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-amber-600 font-bold transition-colors group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Retour au catalogue
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Left: Product Image -->
        <div class="lg:col-span-7">
            <div class="relative group">
                <div class="absolute -inset-4 bg-amber-500/5 rounded-[2.5rem] blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                <div class="relative bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-2xl shadow-slate-200/50">
                    @if($product->image)
                        <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image) }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-auto object-cover hover:scale-105 transition-transform duration-1000">
                    @else
                        <div class="aspect-video bg-slate-50 flex flex-col items-center justify-center text-slate-500">
                            <svg class="w-20 h-20 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-bold uppercase tracking-widest text-xs">Aperçu non disponible</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detailed Description -->
            <div class="mt-12 surface p-8 md:p-12">
                <h3 class="text-2xl font-black text-slate-900 mb-6 tracking-tight">Description détaillée</h3>
                <div class="prose prose-amber max-w-none text-slate-700 font-medium leading-relaxed">
                    {!! $product->description !!}
                </div>
            </div>
        </div>

        <!-- Right: Actions & Stats -->
        <div class="lg:col-span-5 sticky top-24">
            <div class="card-premium p-8 md:p-10 border-amber-100/50">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-100">Disponible</span>
                    <span class="text-slate-300 text-xs">•</span>
                    <span class="text-slate-500 text-xs font-bold">Produit Digital</span>
                </div>

                <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-6 tracking-tighter leading-tight">
                    {{ $product->title }}
                </h1>

                <div class="flex items-baseline gap-2 mb-10">
                    <span class="text-5xl font-black text-amber-600 tracking-tighter">{{ number_format($product->price, $product->currency === 'XOF' ? 0 : 2, ',', ' ') }}</span>
                    <span class="text-xl font-bold text-slate-500 tracking-widest">{{ $product->currency === 'XOF' ? 'FCFA' : $product->currency }}</span>
                </div>

                <div class="space-y-4 mb-10">
                    <a href="{{ route('checkout', $product) }}" class="btn-premium-primary w-full !py-5 text-lg justify-center shadow-amber-200">
                        Acheter maintenant
                        <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </a>
                    
                    <button onclick="shareProduct()" class="w-full flex items-center justify-center gap-3 py-4 px-6 rounded-2xl bg-slate-50 text-slate-600 font-bold hover:bg-slate-100 transition-colors border border-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Partager ce produit
                    </button>
                </div>

                <!-- Trust Points -->
                <div class="border-t border-slate-100 pt-8 space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Paiement ultra-sécurisé</p>
                            <p class="text-xs text-slate-600 font-medium mt-1 leading-relaxed">Transactions protégées par Chariow (Mobile Money, Carte).</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Accès instantané</p>
                            <p class="text-xs text-slate-600 font-medium mt-1 leading-relaxed">Téléchargez vos fichiers immédiatement après le paiement.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-slate-100 text-center">
                    <a href="{{ url('/dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-amber-600 transition-colors underline underline-offset-8">
                        Consulter mon historique d'achats
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function shareProduct() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $product->title }}',
                text: 'Découvrez ce produit numérique sur DigiStore !',
                url: window.location.href,
            }).catch(console.error);
        } else {
            const el = document.createElement('textarea');
            el.value = window.location.href;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert('Lien copié dans le presse-papier !');
        }
    }
</script>
@endsection
