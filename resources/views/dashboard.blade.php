<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Mon Espace Personnel</h1>
                <p class="text-slate-500 font-medium mt-1">Gérez vos produits numériques et vos téléchargements.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn-premium-secondary !py-2.5">
                Retour à la boutique
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
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Mes Produits Numériques</h3>
                    @if(isset($orders) && $orders->count() > 0)
                        <span class="px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest border border-indigo-100">
                            {{ $orders->count() }} {{ $orders->count() > 1 ? 'Produits' : 'Produit' }}
                        </span>
                    @endif
                </div>
                
                @if(isset($orders) && $orders->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($orders as $order)
                            @if($order->product)
                            <div class="card-premium group">
                                <div class="p-6">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Digital Asset</span>
                                    </div>

                                    <h4 class="font-black text-lg text-slate-900 mb-2 truncate group-hover:text-indigo-600 transition-colors" title="{{ $order->product->title }}">
                                        {{ $order->product->title }}
                                    </h4>
                                    <p class="text-sm text-slate-400 font-bold mb-8 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $order->created_at->format('d M Y') }}
                                    </p>
                                    
                                    <a href="{{ URL::temporarySignedRoute('download', now()->addHours(24), ['token' => $order->download_token]) }}" target="_blank" class="btn-premium-primary w-full justify-center !py-3">
                                        @if(filter_var($order->product->file_path, FILTER_VALIDATE_URL))
                                            <span>Accéder au lien</span>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        @else
                                            <span>Télécharger</span>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        @endif
                                    </a>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-20 px-6">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h4 class="text-2xl font-black text-slate-900 mb-2">Aucun achat pour le moment</h4>
                        <p class="text-slate-500 font-medium mb-10 max-w-sm mx-auto">Explorez notre boutique pour trouver des produits numériques incroyables.</p>
                        <a href="{{ route('products.index') }}" class="btn-premium-primary">
                            Découvrir la boutique
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
