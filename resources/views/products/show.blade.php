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
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-amber-600 font-bold transition-colors group">
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
                        <div class="aspect-video bg-slate-50 flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-20 h-20 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="font-bold uppercase tracking-widest text-xs">Aperçu non disponible</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detailed Description -->
            <div class="mt-12 surface p-8 md:p-12">
                <h3 class="text-2xl font-black text-slate-900 mb-6 tracking-tight">Description détaillée</h3>
                <div class="prose prose-indigo max-w-none text-slate-600 font-medium leading-relaxed">
                    {!! $product->description !!}
                </div>
            </div>

            <!-- Testimonials -->
            @if($product->testimonials && is_array($product->testimonials) && count($product->testimonials) > 0)
                <div class="mt-12 surface p-8 md:p-12">
                    <h3 class="text-2xl font-black text-slate-900 mb-6 tracking-tight">Ce qu'en disent nos clients</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($product->testimonials as $testimonial)
                            <div class="rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                                <img src="{{ filter_var($testimonial, FILTER_VALIDATE_URL) ? $testimonial : asset('storage/' . $testimonial) }}" alt="Témoignage client" class="w-full h-auto object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Actions & Stats -->
        <div class="lg:col-span-5 sticky top-24">
            <div class="card-premium p-8 md:p-10 border-indigo-100/50">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-100">Disponible</span>
                    <span class="text-slate-300 text-xs">•</span>
                    <span class="text-slate-400 text-xs font-bold">Produit Digital</span>
                </div>

                <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-6 tracking-tighter leading-tight">
                    {{ $product->title }}
                </h1>

                <div class="flex items-baseline gap-2 mb-10">
                    <span class="text-5xl font-black text-amber-600 tracking-tighter">{{ number_format($product->price, $product->currency === 'XOF' ? 0 : 2, ',', ' ') }}</span>
                    <span class="text-xl font-bold text-slate-400 tracking-widest">{{ $product->currency === 'XOF' ? 'FCFA' : $product->currency }}</span>
                </div>

                <div class="space-y-4 mb-10">
                    <a href="{{ route('checkout', $product) }}" class="btn-premium-primary w-full !py-5 text-lg justify-center shadow-indigo-200">
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
                        <div class="w-full">
                            <p class="text-sm font-bold text-slate-900 mb-2">Paiement ultra-sécurisé</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <!-- Visa -->
                                <div class="h-8 bg-slate-50 border border-slate-100 rounded-lg px-2 flex items-center justify-center">
                                    <svg class="h-4" viewBox="0 0 38 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.8824 0.380859L9.13456 11.5229H5.0667L2.43343 2.91506C2.26189 2.21557 2.11584 1.95475 1.57946 1.63665C0.777033 1.1541 0.163412 0.880859 0 0.771239L0.0986938 0.380859H5.2169C5.87342 0.380859 6.46985 0.814917 6.63291 1.62319L7.843 6.9531L10.5379 0.380859H13.8824ZM26.9698 7.82859C26.9955 4.8878 22.8462 4.72624 22.8633 3.39801C22.8676 2.98064 23.2538 2.52288 24.1678 2.39263C24.627 2.32935 25.6868 2.29344 27.0126 2.91054L27.5361 0.448184C26.8238 0.187869 26.0128 0 24.9658 0C21.8463 0 19.6793 1.67705 19.6536 4.09594C19.6279 5.8821 21.237 6.87614 22.4471 7.46853C23.6872 8.07436 24.1034 8.45582 24.1034 9.00782C24.0991 9.84711 23.108 10.2285 22.2198 10.2285C20.8038 10.2285 19.9885 9.84711 19.3491 9.5428L18.8042 12.0648C19.5165 12.3969 20.615 12.6661 21.7564 12.6706C25.0734 12.6706 27.2229 11.0234 27.2486 8.56108C27.2486 8.2469 27.2229 8.02249 26.9698 7.82859ZM35.0323 12.527H38.2548L35.7317 0.380859H32.964C32.3976 0.380859 31.9084 0.730595 31.6853 1.25565L27.0683 12.527H30.4082L31.0776 10.669H35.1396L35.4528 12.527H35.0323ZM32.0286 8.16965L33.7021 3.5147L34.6633 8.16965H32.0286ZM18.5253 0.380859H15.6546L12.3076 12.527H15.1783L18.5253 0.380859Z" fill="#1434CB"/>
                                    </svg>
                                </div>
                                <!-- Mastercard -->
                                <div class="h-8 bg-slate-50 border border-slate-100 rounded-lg px-2 flex items-center justify-center">
                                    <svg class="h-5" viewBox="0 0 32 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="10" cy="10" r="10" fill="#EB001B"/>
                                        <circle cx="22" cy="10" r="10" fill="#F79E1B"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16 17.9947C18.1566 16.2991 19.5 13.7997 19.5 11C19.5 8.20035 18.1566 5.70087 16 4.00525C13.8434 5.70087 12.5 8.20035 12.5 11C12.5 13.7997 13.8434 16.2991 16 17.9947Z" fill="#FF5F00"/>
                                    </svg>
                                </div>
                                <!-- Mobile Money Providers -->
                                <div class="h-8 bg-[#FFCC00] rounded-lg px-3 flex items-center justify-center shadow-sm">
                                    <span class="text-[11px] font-black text-slate-900 tracking-tight">MTN</span>
                                </div>
                                <div class="h-8 bg-[#0055A5] rounded-lg px-3 flex items-center justify-center shadow-sm">
                                    <span class="text-[11px] font-black text-white tracking-tight">MOOV</span>
                                </div>
                                <div class="h-8 bg-[#E3000F] rounded-lg px-3 flex items-center justify-center shadow-sm">
                                    <span class="text-[11px] font-black text-white tracking-tight">CELTIS</span>
                                </div>
                                <div class="h-8 bg-[#00A1DF] rounded-lg px-3 flex items-center justify-center shadow-sm">
                                    <span class="text-[11px] font-black text-white tracking-tight">WAVE</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">Accès instantané</p>
                            <p class="text-xs text-slate-500 font-medium mt-1 leading-relaxed">Téléchargez vos fichiers immédiatement après le paiement.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-slate-100 text-center">
                    <a href="{{ url('/dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-amber-600 transition-colors underline underline-offset-8">
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
