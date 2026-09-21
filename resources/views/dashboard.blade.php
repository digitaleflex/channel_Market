<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight font-display">Ma Bibliothèque Numérique</h1>
                </div>
                <p class="text-slate-500 font-medium text-sm">Retrouvez tous vos e-books, livres audio et guides acquis sur All_Books.</p>
            </div>
            <a href="{{ route('products.index') }}#catalogue" class="btn-premium-secondary !py-2.5">
                Explorer le catalogue
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-app">
            @if (session('success'))
                <div class="mb-8 animate-float">
                    <div class="glass border-emerald-200/50 bg-emerald-50/80 text-emerald-800 px-6 py-4 rounded-2xl flex items-center gap-3" role="alert">
                        <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="surface p-8 md:p-12">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight font-display">Mes Livres & E-books</h3>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">Accès illimité et téléchargement sécurisé 24/7.</p>
                    </div>
                    @if(isset($orders) && $orders->count() > 0)
                        <span class="px-4 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-black uppercase tracking-widest border border-amber-200">
                            {{ $orders->count() }} {{ $orders->count() > 1 ? 'Livres acquis' : 'Livre acquis' }}
                        </span>
                    @endif
                </div>
                
                @if(isset($orders) && $orders->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        @foreach($orders as $order)
                            @if($order->product)
                            <div class="card-premium group flex flex-col h-full bg-white border border-slate-100 hover:border-amber-200">
                                <!-- Cover with spine shadow -->
                                <div class="relative aspect-[3/4] overflow-hidden block bg-slate-100">
                                    <div class="absolute inset-y-0 left-0 w-3 bg-gradient-to-r from-black/30 via-black/10 to-transparent z-10 pointer-events-none"></div>

                                    @if($order->product->image)
                                        <img src="{{ filter_var($order->product->image, FILTER_VALIDATE_URL) ? $order->product->image : asset('storage/' . $order->product->image) }}" 
                                             alt="{{ $order->product->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center p-6 text-center text-white">
                                            <svg class="w-12 h-12 text-amber-400 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            <span class="text-xs font-bold">{{ $order->product->title }}</span>
                                        </div>
                                    @endif

                                    <!-- Format Badge -->
                                    <div class="absolute top-3 right-3 z-20">
                                        <span class="badge-premium bg-amber-500 text-white font-black shadow-md">
                                            {{ $order->product->format ?? 'PDF' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6 flex flex-col flex-grow">
                                    <!-- Author -->
                                    @if($order->product->author)
                                        <p class="text-xs font-black text-amber-600 uppercase tracking-wider mb-1">
                                            {{ $order->product->author }}
                                        </p>
                                    @endif

                                    <h4 class="font-black text-base text-slate-900 mb-2 line-clamp-2 leading-snug group-hover:text-amber-600 transition-colors" title="{{ $order->product->title }}">
                                        {{ $order->product->title }}
                                    </h4>

                                    <p class="text-xs text-slate-400 font-medium mb-6 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Acquis le {{ $order->created_at->format('d M Y') }}
                                    </p>
                                    
                                    <div class="mt-auto pt-4 border-t border-slate-50">
                                        <a href="{{ URL::temporarySignedRoute('download', now()->addHours(48), ['token' => $order->download_token]) }}" target="_blank" class="btn-premium-primary w-full justify-center !py-2.5 !text-xs !rounded-xl">
                                            @if(filter_var($order->product->file_path, FILTER_VALIDATE_URL))
                                                <span>Accéder au livre</span>
                                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            @else
                                                <span>Télécharger ({{ $order->product->format ?? 'PDF' }})</span>
                                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-20 px-6">
                        <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-6 text-amber-600">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h4 class="text-2xl font-black text-slate-900 mb-2 font-display">Votre bibliothèque est vide</h4>
                        <p class="text-slate-500 font-medium mb-8 max-w-sm mx-auto text-sm">Explorez la librairie All_Books et commencez à lire vos ouvrages favoris dès aujourd'hui.</p>
                        <a href="{{ route('products.index') }}#catalogue" class="btn-premium-primary">
                            Découvrir le catalogue
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
