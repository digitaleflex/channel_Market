<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-900 tracking-tight font-display">
                    {{ __('Catalogue des Livres') }}
                </h2>
                <p class="text-slate-500 font-medium mt-1">Gérez les livres numériques, e-books et extraits en vente sur All_Books.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn-premium-primary px-6 py-3.5 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Ajouter un livre
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-app">
            
            @if (session('success'))
                <div class="glass border-emerald-200 text-emerald-800 px-6 py-4 rounded-[1.5rem] mb-8 flex items-center gap-3 animate-float bg-emerald-50/90">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="card-premium">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-[10px] text-slate-500 uppercase tracking-[0.2em]">
                                <th class="px-8 py-5 font-black">Livre</th>
                                <th class="px-8 py-5 font-black">Auteur</th>
                                <th class="px-8 py-5 font-black">Catégorie / Format</th>
                                <th class="px-8 py-5 font-black">Prix</th>
                                <th class="px-8 py-5 font-black text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($products as $product)
                                <tr class="group hover:bg-slate-50/60 transition-colors duration-200">
                                    <!-- Book Title & Cover -->
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-16 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200 shadow-sm relative">
                                                <div class="absolute inset-y-0 left-0 w-1.5 bg-black/20 pointer-events-none"></div>
                                                @if($product->image)
                                                    <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-amber-50 text-amber-500">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-900 text-base tracking-tight group-hover:text-amber-600 transition-colors">{{ $product->title }}</div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    @if(filter_var($product->file_path, FILTER_VALIDATE_URL))
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100">LIEN CLOUD</span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">FICHIER STOCKÉ</span>
                                                    @endif
                                                    @if($product->sample_file)
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">EXTRAIT DISPO</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Author -->
                                    <td class="px-8 py-5">
                                        <div class="text-sm font-bold text-slate-800">
                                            {{ $product->author ?: '—' }}
                                        </div>
                                        @if($product->pages_count)
                                            <div class="text-xs text-slate-400 font-medium">{{ $product->pages_count }} pages</div>
                                        @endif
                                    </td>

                                    <!-- Category & Format -->
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col items-start gap-1">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                                {{ $product->category ?: 'Général' }}
                                            </span>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-800">
                                                {{ $product->format ?? 'PDF' }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Price -->
                                    <td class="px-8 py-5">
                                        <div class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 font-black text-sm">
                                            {{ number_format($product->price, 0, '', ' ') }}
                                            <span class="text-[10px] font-bold opacity-75">CFA</span>
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('products.show', $product) }}" target="_blank" class="p-2 rounded-xl text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Voir sur le site">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-premium-secondary !py-2 !px-3 inline-flex items-center gap-1 text-xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Modifier
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer définitivement ce livre ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 rounded-xl text-rose-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Supprimer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center gap-4">
                                            <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                            <div class="text-slate-600 font-bold">Aucun livre dans le catalogue de la boutique.</div>
                                            <a href="{{ route('admin.products.create') }}" class="btn-premium-primary !py-2.5 !px-6 text-sm">Ajouter le premier livre</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($products, 'hasPages') && $products->hasPages())
                    <div class="p-8 bg-slate-50/50 border-t border-slate-100">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
