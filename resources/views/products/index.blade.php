@extends('layouts.public')

@php
    $title = 'Boutique de Produits Numériques Premium';
    $description = 'Découvrez notre collection exclusive de scripts, e-books et ressources numériques premium. Téléchargement instantané après paiement sécurisé.';
@endphp

@section('content')
<div class="container-app">
    
    <!-- Hero Section -->
    <div class="relative py-20 md:py-32 overflow-hidden rounded-[3rem] bg-slate-900 mb-16 shadow-2xl">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_50%,rgba(245,158,11,0.2),transparent_50%)]"></div>
        </div>
        
        <div class="relative z-10 text-center px-4">
            <span class="inline-block px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-400 text-xs font-bold uppercase tracking-widest mb-6 border border-amber-500/20">
                Catalogue Premium 2026
            </span>
            <h1 class="text-4xl md:text-7xl font-black tracking-tighter text-white mb-8 leading-tight">
                Découvrez nos <span class="text-amber-400 italic">Produits</span> <br> Numériques d'Elite
            </h1>
            <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed font-medium">
                Propulsez vos projets à la vitesse supérieure avec nos ressources premium soigneusement élaborés par des experts.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#catalogue" class="btn-premium-primary">Parcourir la collection</a>
                <a href="{{ route('register') }}" class="btn-premium bg-white/5 text-white border border-white/10 hover:bg-white/10">Créer un compte</a>
            </div>
        </div>
    </div>

    <!-- Catalogue Header -->
    <div id="catalogue" class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
        <div>
            <h2 class="text-3xl font-black tracking-tight text-slate-900 mb-2">Notre Collection</h2>
            <p class="text-slate-500 font-medium italic">Accès instantané. Qualité garantie.</p>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 py-4">
        @forelse($products as $product)
            <div class="card-premium group flex flex-col h-full bg-white">
                <!-- Image Container -->
                <div class="relative aspect-[4/3] overflow-hidden">
                    @if($product->image)
                        <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center">
                            <svg class="w-12 h-12 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    
                    <!-- Floating Price Tag -->
                    <div class="absolute bottom-4 right-4 glass px-5 py-2.5 rounded-2xl shadow-xl border-white/50 backdrop-blur-xl">
                        <span class="text-lg font-black text-slate-900">{{ number_format($product->price, $product->currency === 'XOF' ? 0 : 2, ',', ' ') }} <small class="text-[10px] text-slate-500 uppercase tracking-tighter">{{ $product->currency === 'XOF' ? 'CFA' : $product->currency }}</small></span>
                    </div>

                    <!-- Category Overlay (Example) -->
                    <div class="absolute top-4 left-4">
                        <span class="badge-premium bg-slate-900/80 text-white backdrop-blur shadow-lg">Promo</span>
                    </div>
                </div>
                
                <div class="p-8 flex flex-col flex-grow">
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-amber-600 transition-colors duration-300 leading-tight">{{ $product->title }}</h3>
                    <p class="text-slate-500 mb-8 line-clamp-2 text-sm leading-relaxed font-medium">
                        {{ strip_tags($product->description) }}
                    </p>
                    
                    <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                        <a href="{{ route('products.show', $product) }}" class="text-sm font-bold text-amber-600 hover:text-amber-800 flex items-center gap-2 transition-all group/link">
                            Détails du produit
                            <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        
                        <div class="flex -space-x-2">
                             <div class="w-6 h-6 rounded-full bg-slate-100 border-2 border-white"></div>
                             <div class="w-6 h-6 rounded-full bg-amber-100 border-2 border-white"></div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 text-center glass rounded-[3rem] border-dashed border-2 border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Aucun produit disponible</h3>
                <p class="text-slate-500 mt-2 font-medium">Revenez bientôt pour découvrir nos nouveautés exclusives.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

