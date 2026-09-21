@extends('layouts.public')

@php
    $title = 'All_Books — Atelier & Librairie Digitale d\'Excellence';
    $description = 'Collection exclusive d\'e-books de référence, guides stratégiques et livres numériques. Formats PDF & ePub haute résolution, téléchargement instantané et lecture universelle.';
    $featuredBook = $products->first();
@endphp

@section('content')
<div class="container-app">
    
    <!-- ==========================================================================
         EDITORIAL ASYMMETRIC HERO (Light Architectural Aesthetic, No #192230 Background)
         ========================================================================== -->
    <section class="relative py-12 md:py-16 px-6 md:px-12 rounded-[2.5rem] bg-white text-[#192230] mb-16 shadow-[0_12px_45px_rgba(25,34,48,0.05)] border border-slate-200/90 overflow-hidden">
        <!-- Ambient Solar Glow -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#ffcd00]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-20 w-80 h-80 bg-slate-100 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Editorial Column (7 cols) -->
            <div class="lg:col-span-7 flex flex-col justify-center">
                <!-- Atelier Pill Badge -->
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-[#F7F8FA] border border-slate-200 text-[#192230] text-xs font-black uppercase tracking-[0.2em] mb-6 w-max shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#ffcd00]"></span>
                    <span>Atelier All_Books • Librairie Digitale</span>
                </div>
                
                <!-- Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-[#192230] mb-6 leading-[1.1] font-display">
                    Lisez l'Essentiel. <br>
                    <span class="text-[#192230] underline decoration-[#ffcd00] decoration-4 decoration-wavy italic font-serif font-normal">Façonnez votre vision.</span>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-base sm:text-lg text-[#3d474e] max-w-xl mb-8 leading-relaxed font-normal">
                    Une collection sélectionnée avec rigueur pour entrepreneurs, penseurs et professionnels exigeants. Formats PDF & ePub optimisés pour tous écrans, disponibles sans attente.
                </p>

                <!-- Architectural Integrated Search Bar -->
                <form action="{{ route('products.index') }}#catalogue" method="GET" class="w-full max-w-xl mb-8">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="relative flex items-center bg-[#F7F8FA] rounded-2xl shadow-sm p-1.5 border border-slate-200 focus-within:border-[#ffcd00] focus-within:bg-white focus-within:ring-4 focus-within:ring-[#ffcd00]/20 transition-all duration-300">
                        <div class="pl-4 pr-2 text-[#3d474e] pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}"
                               placeholder="Rechercher par titre, auteur ou thématique..." 
                               class="w-full py-3 px-2 text-[#192230] placeholder-slate-400 bg-transparent border-0 focus:ring-0 text-sm font-medium">
                        <button type="submit" class="btn-premium-primary !py-3 !px-6 !text-xs !rounded-xl shrink-0 font-black">
                            Explorer
                        </button>
                    </div>
                </form>

                <!-- Architectural Value Bullets -->
                <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-xs text-[#3d474e] font-bold uppercase tracking-wider">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#ffcd00]"></span>
                        <span>Formats PDF & ePub certifiés</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#ffcd00]"></span>
                        <span>Téléchargement immédiat</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#ffcd00]"></span>
                        <span>MoMo • Moov • Celtiis • Cartes</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: "Coup de Cœur de la Rédaction" Spotlight Showcase (5 cols) -->
            <div class="lg:col-span-5 flex justify-center lg:justify-end">
                @if($featuredBook)
                    <div class="relative w-full max-w-sm bg-[#F7F8FA] border border-slate-200/90 rounded-3xl p-6 shadow-xl text-left group hover:border-[#ffcd00] transition-all duration-500">
                        <!-- Header badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-black uppercase tracking-widest text-[#192230] bg-[#ffcd00] px-3 py-1 rounded-full shadow-sm flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-[#192230]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Coup de Cœur
                            </span>
                            <span class="text-[11px] font-bold text-[#3d474e]">
                                {{ $featuredBook->format ?? 'PDF' }}
                            </span>
                        </div>

                        <!-- 3D Book Presentation -->
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden mb-5 bg-white border border-slate-200 shadow-inner group-hover:shadow-xl transition-all">
                            <!-- Simulated spine shadow on the left -->
                            <div class="absolute inset-y-0 left-0 w-4 bg-gradient-to-r from-black/25 via-black/10 to-transparent z-10 pointer-events-none"></div>

                            @if($featuredBook->image)
                                <img src="{{ filter_var($featuredBook->image, FILTER_VALIDATE_URL) ? $featuredBook->image : asset('storage/' . $featuredBook->image) }}" 
                                     alt="{{ $featuredBook->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-b from-slate-50 to-slate-100">
                                    <svg class="w-16 h-16 text-[#ffcd00] opacity-80 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    <span class="text-xs font-bold text-[#192230]">{{ $featuredBook->title }}</span>
                                </div>
                            @endif

                            <!-- Floating Price Tag -->
                            <div class="absolute bottom-3 right-3 bg-white/95 border border-slate-200 px-3.5 py-1.5 rounded-xl shadow-lg backdrop-blur-md z-20">
                                <span class="text-sm font-black text-[#192230]">{{ number_format($featuredBook->price, $featuredBook->currency === 'XOF' ? 0 : 2, ',', ' ') }} <small class="text-[10px] text-[#3d474e] uppercase font-bold">{{ $featuredBook->currency === 'XOF' ? 'CFA' : $featuredBook->currency }}</small></span>
                            </div>
                        </div>

                        <!-- Book Title & Author -->
                        <div class="mb-4">
                            <p class="text-[10px] font-black text-[#3d474e] uppercase tracking-widest mb-1">
                                {{ $featuredBook->author ?: 'Auteur de référence' }}
                            </p>
                            <h2 class="text-lg font-black text-[#192230] leading-snug line-clamp-2">
                                <a href="{{ route('products.show', $featuredBook) }}" class="hover:text-[#3d474e] transition-colors">
                                    {{ $featuredBook->title }}
                                </a>
                            </h2>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-200">
                            @if($featuredBook->sample_file)
                                <a href="{{ route('products.sample', $featuredBook) }}" target="_blank" class="btn-premium bg-white text-[#3d474e] hover:text-[#192230] hover:bg-slate-50 border border-slate-200 !py-2.5 !px-3 !text-xs !rounded-xl justify-center font-bold">
                                    <svg class="w-3.5 h-3.5 text-[#ffcd00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Extrait
                                </a>
                            @else
                                <a href="{{ route('products.show', $featuredBook) }}" class="btn-premium bg-white text-[#3d474e] hover:text-[#192230] hover:bg-slate-50 border border-slate-200 !py-2.5 !px-3 !text-xs !rounded-xl justify-center font-bold">
                                    Détails
                                </a>
                            @endif
                            <a href="{{ route('checkout', $featuredBook) }}" class="btn-premium-primary !py-2.5 !px-3 !text-xs !rounded-xl justify-center font-black">
                                Acquérir
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </section>

    <!-- ==========================================================================
         LES RAYONS DU SAVOIR (Department Navigation Bar)
         ========================================================================== -->
    <div id="catalogue" class="scroll-mt-28 mb-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ffcd00]"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-[#3d474e]">Catalogue Éditorial</span>
                </div>
                <h2 class="text-3xl font-black tracking-tight text-[#192230] font-display">Les Rayons de la Librairie</h2>
            </div>
            
            @if(!empty($search) || (!empty($selectedCategory) && $selectedCategory !== 'all'))
                <a href="{{ route('products.index') }}#catalogue" class="inline-flex items-center gap-2 text-xs font-black text-rose-600 hover:text-rose-800 uppercase tracking-wider transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Réinitialiser les filtres
                </a>
            @endif
        </div>

        <!-- Horizontal Category Pills -->
        @if(isset($categories) && $categories->count() > 0)
            <div class="flex items-center gap-2.5 overflow-x-auto pb-4 scrollbar-none">
                <a href="{{ route('products.index', array_filter(['search' => $search])) }}#catalogue" 
                   class="px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all duration-300 {{ empty($selectedCategory) || $selectedCategory === 'all' ? 'bg-[#ffcd00] text-[#192230] shadow-md border border-[#ffcd00]' : 'bg-white text-[#3d474e] border border-slate-200 hover:border-[#ffcd00] hover:text-[#192230]' }}">
                    Tous les rayons
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.index', array_filter(['category' => $cat, 'search' => $search])) }}#catalogue" 
                       class="px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all duration-300 {{ $selectedCategory === $cat ? 'bg-[#ffcd00] text-[#192230] shadow-md border border-[#ffcd00]' : 'bg-white text-[#3d474e] border border-slate-200 hover:border-[#ffcd00] hover:text-[#192230]' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ==========================================================================
         L'ÉTAGÈRE CONTEMPORAINE (Book Grid - Luxury Book Presentation)
         ========================================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 py-4">
        @forelse($products as $product)
            <div class="card-premium group flex flex-col h-full bg-white border border-slate-200/80 hover:border-[#ffcd00]">
                
                <!-- Book Cover Container (Vertical 3:4 Aspect with physical book spine simulation) -->
                <a href="{{ route('products.show', $product) }}" class="relative aspect-[3/4] overflow-hidden block bg-slate-100">
                    <!-- Book spine edge simulation (shadow on left) -->
                    <div class="absolute inset-y-0 left-0 w-3.5 bg-gradient-to-r from-black/25 via-black/10 to-transparent z-10 pointer-events-none"></div>

                    @if($product->image)
                        <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image) }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200 flex flex-col items-center justify-center p-6 text-center">
                            <svg class="w-14 h-14 text-[#ffcd00] opacity-80 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span class="text-xs font-bold text-[#192230] uppercase tracking-widest">{{ $product->title }}</span>
                        </div>
                    @endif

                    <!-- Category Badge -->
                    @if($product->category)
                        <div class="absolute top-3 left-4 z-20">
                            <span class="badge-premium bg-white/95 text-[#192230] backdrop-blur-md shadow-sm border border-slate-200 text-[9px] font-bold">
                                {{ $product->category }}
                            </span>
                        </div>
                    @endif

                    <!-- Solar Yellow Format Bookmark Ribbon -->
                    <div class="absolute top-3 right-4 z-20">
                        <span class="badge-premium bg-[#ffcd00] text-[#192230] font-black shadow-md border border-[#ffcd00]">
                            {{ $product->format ?? 'PDF' }}
                        </span>
                    </div>
                    
                    <!-- Floating Price Capsule -->
                    <div class="absolute bottom-3 right-3 bg-white/95 border border-slate-200 px-3.5 py-1.5 rounded-xl shadow-md backdrop-blur-md z-20">
                        <span class="text-sm font-black text-[#192230]">{{ number_format($product->price, $product->currency === 'XOF' ? 0 : 2, ',', ' ') }} <small class="text-[9px] text-[#3d474e] uppercase font-bold">{{ $product->currency === 'XOF' ? 'CFA' : $product->currency }}</small></span>
                    </div>
                </a>
                
                <!-- Book Metadata & Actions -->
                <div class="p-6 flex flex-col flex-grow">
                    <!-- Author Credit -->
                    <div class="flex items-center gap-1.5 text-xs text-[#3d474e] font-extrabold uppercase tracking-wider mb-1.5">
                        <svg class="w-3.5 h-3.5 text-[#ffcd00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        <span>{{ $product->author ?: 'Auteur All_Books' }}</span>
                    </div>

                    <!-- Book Title -->
                    <h3 class="text-base font-black text-[#192230] mb-2 group-hover:text-[#3d474e] transition-colors duration-300 line-clamp-2 leading-snug font-display">
                        <a href="{{ route('products.show', $product) }}">
                            {{ $product->title }}
                        </a>
                    </h3>

                    <!-- Specs details (pages, language) -->
                    <div class="flex items-center gap-3 text-[11px] font-bold text-slate-400 mb-3">
                        @if($product->pages_count)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                {{ $product->pages_count }} pages
                            </span>
                        @endif
                        @if($product->language)
                            <span>• {{ $product->language }}</span>
                        @endif
                    </div>

                    <!-- Excerpt Description -->
                    <p class="text-slate-500 mb-6 line-clamp-2 text-xs leading-relaxed font-normal">
                        {{ strip_tags($product->description) }}
                    </p>
                    
                    <!-- Card Footer Actions -->
                    <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                        @if($product->sample_file)
                            <a href="{{ route('products.sample', $product) }}" target="_blank" class="text-xs font-bold text-[#3d474e] hover:text-[#192230] inline-flex items-center gap-1.5 transition-colors" title="Lire un extrait gratuit">
                                <svg class="w-4 h-4 text-[#ffcd00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Extrait
                            </a>
                        @else
                            <a href="{{ route('products.show', $product) }}" class="text-xs font-bold text-slate-400 hover:text-[#192230] transition-colors">
                                Fiche livre
                            </a>
                        @endif
                        
                        <a href="{{ route('checkout', $product) }}" class="btn-premium-primary !py-2 !px-4 !text-xs !rounded-xl font-black">
                            Acquérir
                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-slate-200/80 p-8 shadow-sm">
                <div class="w-16 h-16 bg-[#ffcd00]/15 rounded-2xl flex items-center justify-center mx-auto mb-4 text-[#192230]">
                    <svg class="w-8 h-8 text-[#ffcd00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-xl font-black text-[#192230] tracking-tight">Aucun livre ne correspond à cette recherche</h3>
                <p class="text-slate-500 mt-2 text-sm font-normal max-w-md mx-auto">Veuillez ajuster votre mot-clé ou réinitialiser les filtres pour découvrir notre catalogue.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}#catalogue" class="btn-premium-primary !py-2.5 !px-6 text-xs font-black">
                        Voir tous les livres
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($products, 'hasPages') && $products->hasPages())
        <div class="mt-14 flex justify-center">
            {{ $products->links() }}
        </div>
    @endif

    <!-- ==========================================================================
         LES ENGAGEMENTS DE L'ATELIER (3 Architectural Pillars)
         ========================================================================== -->
    <section class="mt-24 pt-16 border-t border-slate-200/80">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#F7F8FA] border border-slate-200 text-[#192230] text-[10px] font-black uppercase tracking-[0.2em] mb-4 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[#ffcd00]"></span>
                Manifeste & Garantie
            </div>
            <h2 class="text-3xl font-black text-[#192230] tracking-tight font-display">L'Expérience de Lecture All_Books</h2>
            <p class="text-slate-500 text-sm mt-3 font-medium">Une infrastructure d'achat et de lecture pensée pour votre confort immédiat et durable.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Pillar 1 -->
            <div class="surface p-8 border border-slate-200/80 hover:border-[#ffcd00] transition-all group">
                <div class="text-3xl font-black text-[#ffcd00] font-mono mb-4">01</div>
                <h3 class="text-lg font-black text-[#192230] mb-2 font-display">Formats Universels Sans Contrainte</h3>
                <p class="text-slate-600 text-sm leading-relaxed font-normal">
                    Fichiers PDF et ePub prêts à l'emploi. Lisez en toute fluidité sur votre liseuse (Kindle, Kobo), votre iPad, smartphone ou ordinateur sans application propriétaire.
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="surface p-8 border border-slate-200/80 hover:border-[#ffcd00] transition-all group">
                <div class="text-3xl font-black text-[#ffcd00] font-mono mb-4">02</div>
                <h3 class="text-lg font-black text-[#192230] mb-2 font-display">Accès Perpétuel & Sauvegarde</h3>
                <p class="text-slate-600 text-sm leading-relaxed font-normal">
                    Chaque ouvrage acquis est rattaché à vie à votre compte personnel. Retéléchargez vos livres à tout moment depuis votre bibliothèque sans frais supplémentaires.
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="surface p-8 border border-slate-200/80 hover:border-[#ffcd00] transition-all group">
                <div class="text-3xl font-black text-[#ffcd00] font-mono mb-4">03</div>
                <h3 class="text-lg font-black text-[#192230] mb-2 font-display">Règlements Sécurisés & Instantanés</h3>
                <p class="text-slate-600 text-sm leading-relaxed font-normal">
                    Paiement direct par MTN Mobile Money, Moov Money, Celtiis et Cartes Bancaires. Confirmation immédiate et envoi automatique de votre lien par SMS et Email.
                </p>
            </div>
        </div>
    </section>

    

</div>
@endsection
