<x-app-layout>
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            trix-editor {
                min-height: 250px !important;
                border-radius: 1.25rem !important;
                border: 2px solid #f1f5f9 !important;
                padding: 1.25rem !important;
                background-color: white !important;
                color: #1e293b !important;
                font-family: 'Inter', sans-serif !important;
                font-weight: 500 !important;
                line-height: 1.625 !important;
            }
            trix-editor:focus {
                border-color: #6366f1 !important;
                outline: none !important;
                box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
            }
            trix-toolbar .trix-button-group {
                border-color: #f1f5f9 !important;
                margin-bottom: 0.75rem !important;
            }
            trix-toolbar .trix-button {
                border-bottom: none !important;
            }
            /* Hide attachment button as we don't have backend logic for it yet */
            .trix-button--icon-attach { display: none !important; }
        </style>
    @endpush

    @push('scripts')
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @endpush
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-900 tracking-tight font-display">
                    {{ __('Ajouter un Produit') }}
                </h2>
                <p class="text-slate-500 font-medium mt-1">Créez un nouveau produit digital pour votre marketplace.</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn-premium-secondary !py-2.5">
                Retour au catalogue
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12" x-data="{ productType: 'file' }">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="space-y-8">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Titre du produit</label>
                                <input type="text" name="title" required class="input-premium" value="{{ old('title') }}" placeholder="Ex: Pack de Design Premium">
                                @error('title') <span class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Description complète</label>
                                <input id="description" type="hidden" name="description" value="{{ old('description') }}">
                                <trix-editor input="description" class="trix-content" placeholder="Décrivez votre produit en détail..."></trix-editor>
                                @error('description') <span class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Prix de vente (CFA)</label>
                                <div class="relative">
                                    <input type="number" step="1" name="price" required class="input-premium pl-12" value="{{ old('price') }}" placeholder="0">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">CFA</div>
                                </div>
                                @error('price') <span class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-8">
                            <div class="p-6 rounded-3xl bg-indigo-50/50 border border-indigo-100/50">
                                <label class="block text-xs font-black uppercase tracking-widest text-indigo-400 mb-4">Produit Numérique à vendre</label>
                                
                                <div class="flex gap-4 mb-6">
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="product_type" value="file" x-model="productType" class="hidden">
                                        <div class="p-3 rounded-xl border-2 text-center transition-all" :class="productType === 'file' ? 'border-indigo-500 bg-white text-indigo-600 shadow-md' : 'border-slate-100 text-slate-400 group-hover:border-slate-200'">
                                            <span class="text-xs font-black uppercase">Fichier (PDF, Vidéo)</span>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="product_type" value="link" x-model="productType" class="hidden">
                                        <div class="p-3 rounded-xl border-2 text-center transition-all" :class="productType === 'link' ? 'border-indigo-500 bg-white text-indigo-600 shadow-md' : 'border-slate-100 text-slate-400 group-hover:border-slate-200'">
                                            <span class="text-xs font-black uppercase">Lien Drive / URL</span>
                                        </div>
                                    </label>
                                </div>

                                <div x-show="productType === 'file'" x-transition>
                                    <div class="relative group">
                                        <input type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" :required="productType === 'file'">
                                        <div class="border-2 border-dashed border-indigo-200 rounded-2xl p-8 text-center group-hover:bg-white group-hover:border-indigo-400 transition-all duration-300">
                                            <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                            </div>
                                            <p class="text-sm font-bold text-slate-700">Télécharger le fichier</p>
                                        </div>
                                    </div>
                                    @error('file') <span class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                                </div>

                                <div x-show="productType === 'link'" x-transition x-cloak>
                                    <input type="url" name="drive_link" class="input-premium" value="{{ old('drive_link') }}" placeholder="https://drive.google.com/..." :required="productType === 'link'">
                                    <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-wider">Assurez-vous que le lien est accessible aux acheteurs.</p>
                                    @error('drive_link') <span class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Image de présentation (Obligatoire)</label>
                                <div class="space-y-4">
                                    <input type="file" name="image_file" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer transition shadow-lg shadow-slate-900/10">
                                </div>
                                @error('image_file') <p class="text-rose-500 text-xs font-bold mt-2 block">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>


                    <div class="flex items-center justify-end gap-4 mt-12 pt-8 border-t border-slate-50">
                        <a href="{{ route('admin.products.index') }}" class="font-bold text-slate-400 hover:text-slate-600 transition-colors px-6">Annuler</a>
                        <button type="submit" class="btn-premium-primary min-w-[200px]">
                            Lancer le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

