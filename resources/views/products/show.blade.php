@extends('layouts.public')

@php
    $title = $product->title . ' — ' . ($product->author ? 'de ' . $product->author : 'All_Books');
    $description = Str::limit(strip_tags($product->description), 160);
    $ogImage = filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image);
@endphp

@section('content')
@push('tracking-events')
    <script>
        if (typeof fbq === 'function') {
            fbq('track', 'ViewContent', {
                content_name: '{{ addslashes($product->title) }}',
                content_ids: ['{{ $product->id }}'],
                content_type: 'product',
                currency: '{{ $product->currency ?? 'XOF' }}'
            });
        }
        if (typeof gtag === 'function') {
            gtag('event', 'view_item', {
                items: [{
                    item_id: '{{ $product->id }}',
                    item_name: '{{ addslashes($product->title) }}',
                    price: {{ $product->price }},
                    currency: '{{ $product->currency ?? 'XOF' }}'
                }]
            });
        }
    </script>
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@@type": "Book",
      "name": "{{ addslashes($product->title) }}",
      "image": "{{ $ogImage }}",
      "author": {
        "@@type": "Person",
        "name": "{{ addslashes($product->author ?: 'Auteur All_Books') }}"
      },
      "bookFormat": "https://schema.org/EBook",
      "inLanguage": "{{ $product->language ?? 'Français' }}",
      @if($product->pages_count)
      "numberOfPages": {{ $product->pages_count }},
      @endif
      @if($product->isbn)
      "isbn": "{{ $product->isbn }}",
      @endif
      "description": "{{ addslashes(Str::limit(strip_tags($product->description), 160)) }}",
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

<div class="container-app py-8 md:py-12" x-data="{ showSampleModal: false }">
    <!-- Breadcrumbs / Back Link -->
    <div class="mb-8">
        <a href="{{ route('products.index') }}#catalogue" class="inline-flex items-center gap-2 text-[#3d474e] hover:text-[#192230] font-bold text-sm transition-colors group">
            <svg class="w-4 h-4 text-[#ffcd00] group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
            Retour au catalogue All_Books
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        <!-- Left Column: Book Presentation & Description -->
        <div class="lg:col-span-7">
            <!-- Book Cover Showcase with 3D Depth -->
            <div class="relative group max-w-md mx-auto lg:mx-0">
                <div class="absolute -inset-4 bg-[#ffcd00]/15 rounded-[2.5rem] blur-2xl opacity-60 group-hover:opacity-100 transition-opacity duration-700"></div>
                
                <div class="relative bg-slate-100 rounded-[2rem] overflow-hidden border border-slate-200/80 shadow-2xl aspect-[3/4]">
                    <!-- Left Spine Shadow simulation -->
                    <div class="absolute inset-y-0 left-0 w-4 bg-gradient-to-r from-black/25 via-black/10 to-transparent z-10 pointer-events-none"></div>

                    @if($product->image)
                        <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image) }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-b from-slate-50 to-slate-100 flex flex-col items-center justify-center p-8 text-center text-[#192230]">
                            <svg class="w-20 h-20 mb-4 text-[#ffcd00] opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span class="font-bold text-lg text-[#192230]">{{ $product->title }}</span>
                        </div>
                    @endif
                </div>

                <!-- Sample Excerpt Trigger under Cover (if available) -->
                @if($product->sample_file)
                    <div class="mt-6 text-center">
                        <button type="button" @click="showSampleModal = true" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-[#ffcd00] hover:bg-[#ffd833] text-[#192230] font-black text-sm shadow-md transition-all duration-300 active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4 text-[#192230]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Feuilleter l'extrait en ligne (Gratuit)
                        </button>
                    </div>
                @endif
            </div>

            <!-- Detailed Description / Book Summary -->
            <div class="mt-12 surface p-8 md:p-10 border border-slate-200/80">
                <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-[#ffcd00]/20 flex items-center justify-center text-[#192230]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-[#192230] tracking-tight font-display">Résumé & Présentation de l'ouvrage</h3>
                </div>
                <div class="prose max-w-none text-[#3d474e] font-normal leading-relaxed text-sm sm:text-base">
                    {!! $product->description !!}
                </div>
            </div>

            <!-- Testimonials & Reviews -->
            @if($product->testimonials && is_array($product->testimonials) && count($product->testimonials) > 0)
                <div class="mt-12 surface p-8 md:p-10 border border-slate-200/80">
                    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-[#ffcd00]/20 flex items-center justify-center text-[#192230]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-[#192230] tracking-tight font-display">Avis des lecteurs</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($product->testimonials as $testimonial)
                            <div class="rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                                <img src="{{ filter_var($testimonial, FILTER_VALIDATE_URL) ? $testimonial : asset('storage/' . $testimonial) }}" alt="Témoignage lecteur" class="w-full h-auto object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Related Books in the same category -->
            @if(isset($relatedBooks) && $relatedBooks->count() > 0)
                <div class="mt-12 surface p-8 md:p-10 border border-slate-200/80">
                    <h3 class="text-xl font-black text-[#192230] mb-6 tracking-tight font-display">Autres parutions dans ce rayon</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        @foreach($relatedBooks as $related)
                            <a href="{{ route('products.show', $related) }}" class="group block">
                                <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-slate-100 mb-3 shadow-md group-hover:shadow-lg transition-all duration-300">
                                    <img src="{{ filter_var($related->image, FILTER_VALIDATE_URL) ? $related->image : asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                                <h4 class="font-bold text-sm text-[#192230] line-clamp-1 group-hover:text-[#3d474e] transition-colors">{{ $related->title }}</h4>
                                <p class="text-xs text-slate-500 line-clamp-1">{{ $related->author ?: 'All_Books' }}</p>
                                <span class="text-xs font-black text-[#192230] bg-[#ffcd00] px-2 py-0.5 rounded-lg mt-1 inline-block">{{ number_format($related->price, 0, ',', ' ') }} CFA</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions, Specs & Checkout Info -->
        <div class="lg:col-span-5 sticky top-24">
            <div class="card-premium p-8 md:p-10 border-slate-200/80 shadow-xl">
                <!-- Badges Header -->
                <div class="flex items-center gap-2 flex-wrap mb-4">
                    @if($product->category)
                        <span class="px-3 py-1 rounded-full bg-white text-[#192230] text-[10px] font-black uppercase tracking-widest border border-slate-200">
                            {{ $product->category }}
                        </span>
                    @endif
                    <span class="px-3 py-1 rounded-full bg-[#ffcd00] text-[#192230] text-[10px] font-black uppercase tracking-widest">
                        Format {{ $product->format ?? 'PDF' }}
                    </span>
                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-black uppercase tracking-widest border border-emerald-200">
                        Disponible
                    </span>
                </div>

                <!-- Author -->
                @if($product->author)
                    <div class="flex items-center gap-2 text-xs font-black text-[#3d474e] uppercase tracking-widest mb-2">
                        <svg class="w-3.5 h-3.5 text-[#ffcd00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        <span>{{ $product->author }}</span>
                    </div>
                @endif

                <!-- Book Title -->
                <h1 class="text-2xl md:text-3xl font-black text-[#192230] mb-6 tracking-tight leading-tight font-display">
                    {{ $product->title }}
                </h1>

                <!-- Price Capsule -->
                <div class="flex items-baseline gap-2 mb-6 bg-[#ffcd00]/15 p-4 rounded-2xl border border-[#ffcd00]/30">
                    <span class="text-3xl md:text-4xl font-black text-[#192230] tracking-tight">{{ number_format($product->price, $product->currency === 'XOF' ? 0 : 2, ',', ' ') }}</span>
                    <span class="text-sm font-bold text-[#3d474e] tracking-wider uppercase">{{ $product->currency === 'XOF' ? 'FCFA' : $product->currency }}</span>
                </div>

                <!-- Book Specifications Table -->
                <div class="space-y-3 mb-6 bg-slate-50 rounded-2xl p-5 border border-slate-100 text-xs sm:text-sm">
                    <div class="flex justify-between items-center py-1 border-b border-slate-200/50">
                        <span class="text-slate-500 font-medium">Format de lecture</span>
                        <span class="font-extrabold text-[#192230]">{{ $product->format ?? 'PDF' }}</span>
                    </div>
                    @if($product->pages_count)
                        <div class="flex justify-between items-center py-1 border-b border-slate-200/50">
                            <span class="text-slate-500 font-medium">Nombre de pages</span>
                            <span class="font-extrabold text-[#192230]">{{ $product->pages_count }} pages</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-1 border-b border-slate-200/50">
                        <span class="text-slate-500 font-medium">Langue</span>
                        <span class="font-extrabold text-[#192230]">{{ $product->language ?: 'Français' }}</span>
                    </div>
                    @if($product->publication_year)
                        <div class="flex justify-between items-center py-1 border-b border-slate-200/50">
                            <span class="text-slate-500 font-medium">Année de publication</span>
                            <span class="font-extrabold text-[#192230]">{{ $product->publication_year }}</span>
                        </div>
                    @endif
                    @if($product->isbn)
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">ISBN / Référence</span>
                            <span class="font-mono text-xs font-bold text-[#192230]">{{ $product->isbn }}</span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3 mb-6">
                    <a href="{{ route('checkout', $product) }}" class="btn-premium-primary w-full !py-4 text-sm justify-center font-black">
                        <span>Acheter et télécharger immédiatement</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </a>
                    
                    @if($product->sample_file)
                        <button type="button" @click="showSampleModal = true" class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-2xl bg-slate-100 text-[#192230] font-bold hover:bg-[#ffcd00] transition-all border border-slate-200 text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 text-[#192230]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Feuilleter l'extrait
                        </button>
                    @endif

                    <button onclick="shareProduct()" class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-2xl bg-white text-slate-500 font-bold hover:text-[#192230] hover:bg-slate-50 transition-colors border border-slate-200 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                        Partager ce livre
                    </button>
                </div>

                <!-- Trust Points -->
                <div class="border-t border-slate-100 pt-6 space-y-4 text-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#ffcd00]/20 text-[#192230] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-[#192230]">Téléchargement instantané</p>
                            <p class="text-slate-500 mt-0.5">Lien d'accès envoyé dès la validation du paiement.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#ffcd00]/20 text-[#192230] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-[#192230]">Lecture multi-supports</p>
                            <p class="text-slate-500 mt-0.5">Compatible liseuses Kindle, Kobo, iPad, tablettes et PC.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-[#ffcd00]/20 text-[#192230] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-[#192230]">Paiement certifié</p>
                            <p class="text-slate-500 mt-0.5">MTN Mobile Money, Moov, Celtiis et Cartes Bancaires.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                    <a href="{{ url('/dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-[#192230] transition-colors underline underline-offset-4">
                        Accéder à Ma Bibliothèque
                    </a>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>

    <!-- In-Browser PDF/Sample Preview Modal -->
    @if($product->sample_file)
    <div x-show="showSampleModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="showSampleModal = false"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" @click="showSampleModal = false"></div>

        <!-- Modal Card -->
        <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden z-10">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-white text-[#192230] flex items-center justify-between border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#ffcd00]/20 text-[#192230] flex items-center justify-center font-black">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm sm:text-base text-[#192230] tracking-tight line-clamp-1">Extrait gratuit : {{ $product->title }}</h4>
                        <p class="text-xs text-[#3d474e]">{{ $product->author ?: 'All_Books' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('products.sample', $product) }}" target="_blank" download class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-[#192230] text-xs font-bold transition-all border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Ouvrir dans un nouvel onglet
                    </a>

                    <button type="button" @click="showSampleModal = false" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-[#192230] flex items-center justify-center transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body: Embedded PDF / Viewer -->
            <div class="flex-grow w-full bg-slate-100 relative">
                <iframe src="{{ route('products.sample', $product) }}#toolbar=0" class="w-full h-full border-0" title="Lecture extrait {{ $product->title }}"></iframe>
            </div>

            <!-- Modal Footer Call to Action -->
            <div class="px-6 py-4 bg-white border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-500 hidden sm:block">Cet extrait vous plaît ? Commandez la version complète pour continuer la lecture.</p>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <button type="button" @click="showSampleModal = false" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs">
                        Fermer
                    </button>
                    <a href="{{ route('checkout', $product) }}" class="btn-primary !py-2.5 !px-6 text-xs flex items-center gap-2">
                        <span>Acheter l'ouvrage complet ({{ number_format($product->price, 0, ',', ' ') }} CFA)</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    function shareProduct() {
        if (navigator.share) {
            navigator.share({
                title: '{{ addslashes($product->title) }}',
                text: 'Découvrez ce livre numérique sur All_Books !',
                url: window.location.href,
            }).catch(console.error);
        } else {
            const el = document.createElement('textarea');
            el.value = window.location.href;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            alert('Lien du livre copié dans le presse-papier !');
        }
    }
</script>
@endsection
