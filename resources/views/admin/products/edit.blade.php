<x-app-layout>
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            trix-editor {
                min-height: 220px !important;
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
                border-color: #f59e0b !important;
                outline: none !important;
                box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1) !important;
            }
            trix-toolbar .trix-button-group {
                border-color: #f1f5f9 !important;
                margin-bottom: 0.75rem !important;
            }
            trix-toolbar .trix-button {
                border-bottom: none !important;
            }
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
                    {{ __('Modifier le Livre') }}
                </h2>
                <p class="text-slate-500 font-medium mt-1">Mise à jour de : {{ $product->title }}</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn-premium-secondary !py-2.5">
                Retour au catalogue
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium">
                @php
                    $isLink = filter_var($product->file_path, FILTER_VALIDATE_URL);
                    $hasSample = ! empty($product->sample_file);
                    $isSampleLink = $hasSample && filter_var($product->sample_file, FILTER_VALIDATE_URL);
                    $sampleInitialType = $hasSample ? ($isSampleLink ? 'link' : 'file') : 'none';
                @endphp
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12" x-data="{ productType: '{{ $isLink ? 'link' : 'file' }}', sampleType: '{{ $sampleInitialType }}' }">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Left Column: Book Details -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Titre du livre *</label>
                                <input type="text" name="title" required class="input-premium" value="{{ old('title', $product->title) }}" placeholder="Ex: Titre de l'ouvrage">
                                @error('title') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Auteur *</label>
                                    <input type="text" name="author" required class="input-premium" value="{{ old('author', $product->author) }}" placeholder="Ex: Nom de l'auteur">
                                    @error('author') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Catégorie / Thème</label>
                                    <input type="text" name="category" list="categories-list" class="input-premium" value="{{ old('category', $product->category) }}" placeholder="Ex: Business & Entrepreneuriat">
                                    <datalist id="categories-list">
                                        @if(isset($existingCategories))
                                            @foreach($existingCategories as $cat)
                                                <option value="{{ $cat }}">
                                            @endforeach
                                        @endif
                                        <option value="Développement Personnel">
                                        <option value="Business & Entrepreneuriat">
                                        <option value="Finance & Investissement">
                                        <option value="Tech & Programmation">
                                        <option value="Littérature & Romans">
                                        <option value="Santé & Bien-être">
                                    </datalist>
                                    @error('category') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Format *</label>
                                    <select name="format" class="input-premium" required>
                                        <option value="PDF" {{ old('format', $product->format) === 'PDF' ? 'selected' : '' }}>PDF</option>
                                        <option value="EPUB" {{ old('format', $product->format) === 'EPUB' ? 'selected' : '' }}>EPUB</option>
                                        <option value="PDF + EPUB" {{ old('format', $product->format) === 'PDF + EPUB' ? 'selected' : '' }}>PDF + EPUB</option>
                                        <option value="Audiobook (MP3)" {{ old('format', $product->format) === 'Audiobook (MP3)' ? 'selected' : '' }}>Audiobook (MP3)</option>
                                        <option value="Kindle / MOBI" {{ old('format', $product->format) === 'Kindle / MOBI' ? 'selected' : '' }}>Kindle / MOBI</option>
                                    </select>
                                    @error('format') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Nb Pages</label>
                                    <input type="number" name="pages_count" class="input-premium" value="{{ old('pages_count', $product->pages_count) }}" placeholder="Ex: 280" min="1">
                                    @error('pages_count') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Langue</label>
                                    <input type="text" name="language" class="input-premium" value="{{ old('language', $product->language ?? 'Français') }}" placeholder="Français">
                                    @error('language') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Année de parution</label>
                                    <input type="number" name="publication_year" class="input-premium" value="{{ old('publication_year', $product->publication_year) }}" placeholder="2026" min="1800" max="2100">
                                    @error('publication_year') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">ISBN / Référence</label>
                                    <input type="text" name="isbn" class="input-premium" value="{{ old('isbn', $product->isbn) }}" placeholder="978-...">
                                    @error('isbn') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Prix de vente (CFA) *</label>
                                    <div class="relative">
                                        <input type="number" step="1" name="price" required class="input-premium pl-14" value="{{ old('price', (int)$product->price) }}" placeholder="2500">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">CFA</div>
                                    </div>
                                    @error('price') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Chariow Product ID</label>
                                    <input type="text" name="chariow_product_id" class="input-premium" value="{{ old('chariow_product_id', $product->chariow_product_id) }}" placeholder="Ex: prd_12345">
                                    @error('chariow_product_id') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Résumé complet de l'ouvrage *</label>
                                <input id="description" type="hidden" name="description" value="{{ old('description', $product->description) }}">
                                <trix-editor input="description" class="trix-content" placeholder="Présentez le livre..."></trix-editor>
                                @error('description') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Right Column: Delivery File, Sample, Cover & Testimonials -->
                        <div class="space-y-6">
                            <!-- Delivery File (Full Book) -->
                            <div class="p-6 rounded-3xl bg-amber-50/50 border border-amber-200/60">
                                <label class="block text-xs font-black uppercase tracking-widest text-amber-800 mb-3">Livre complet à livrer (Achat)</label>
                                
                                <div class="flex gap-3 mb-4">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="product_type" value="file" x-model="productType" class="hidden">
                                        <div class="p-3 rounded-xl border-2 text-center transition-all text-xs font-black uppercase" :class="productType === 'file' ? 'border-amber-500 bg-white text-amber-700 shadow-sm' : 'border-slate-200 text-slate-400'">
                                            Fichier (PDF, EPUB)
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="product_type" value="link" x-model="productType" class="hidden">
                                        <div class="p-3 rounded-xl border-2 text-center transition-all text-xs font-black uppercase" :class="productType === 'link' ? 'border-amber-500 bg-white text-amber-700 shadow-sm' : 'border-slate-200 text-slate-400'">
                                            Lien Drive / Cloud
                                        </div>
                                    </label>
                                </div>

                                <div x-show="productType === 'file'" x-data="{ fileName: '' }">
                                    <div class="relative group">
                                        <input type="file" name="file" @change="fileName = $event.target.files[0]?.name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="border-2 border-dashed border-amber-300 rounded-2xl p-6 text-center group-hover:bg-white transition-colors" :class="fileName ? 'bg-white border-emerald-500' : ''">
                                            <p class="text-xs font-bold text-slate-700" x-text="fileName ? fileName : 'Remplacer le fichier (laisser vide pour conserver l\'actuel)'"></p>
                                        </div>
                                    </div>
                                    @if(!$isLink && $product->file_path)
                                        <p class="text-[11px] text-emerald-600 font-bold mt-2 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Fichier actuel enregistré : {{ basename($product->file_path) }}
                                        </p>
                                    @endif
                                    @error('file') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div x-show="productType === 'link'" x-cloak>
                                    <input type="url" name="drive_link" class="input-premium" value="{{ old('drive_link', $isLink ? $product->file_path : '') }}" placeholder="https://drive.google.com/file/d/..." :required="productType === 'link'">
                                    @error('drive_link') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Free Sample Excerpt -->
                            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-700 mb-2">Extrait gratuit (« Feuilleter le livre »)</label>
                                
                                <div class="flex gap-2 mb-3">
                                    <button type="button" @click="sampleType = 'none'" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors" :class="sampleType === 'none' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-600 border-slate-200'">Aucun</button>
                                    <button type="button" @click="sampleType = 'file'" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors" :class="sampleType === 'file' ? 'bg-amber-600 text-white border-amber-600' : 'bg-white text-slate-600 border-slate-200'">Fichier PDF</button>
                                    <button type="button" @click="sampleType = 'link'" class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors" :class="sampleType === 'link' ? 'bg-amber-600 text-white border-amber-600' : 'bg-white text-slate-600 border-slate-200'">Lien externe</button>
                                </div>
                                <input type="hidden" name="sample_type" :value="sampleType">

                                <div x-show="sampleType === 'file'" x-cloak x-data="{ sampleName: '' }">
                                    <div class="relative group">
                                        <input type="file" name="sample_file_upload" @change="sampleName = $event.target.files[0]?.name" accept=".pdf,.epub,.doc,.docx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center bg-white">
                                            <p class="text-xs font-bold text-slate-600" x-text="sampleName ? sampleName : 'Remplacer l\'extrait PDF (laisser vide pour conserver)'"></p>
                                        </div>
                                    </div>
                                    @if($hasSample && !$isSampleLink)
                                        <p class="text-[11px] text-emerald-600 font-bold mt-1">Extrait actuel actif : {{ basename($product->sample_file) }}</p>
                                    @endif
                                    @error('sample_file_upload') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div x-show="sampleType === 'link'" x-cloak>
                                    <input type="url" name="sample_link" class="input-premium" value="{{ old('sample_link', $isSampleLink ? $product->sample_file : '') }}" placeholder="https://drive.google.com/.../preview">
                                    @error('sample_link') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Book Cover Image -->
                            <div x-data="{ coverName: '' }">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Couverture du livre (Format vertical 2:3 ou 3:4)</label>
                                <div class="flex items-center gap-4 mb-3">
                                    @if($product->image)
                                        <div class="w-14 h-20 rounded-xl overflow-hidden border border-slate-200 shadow-sm flex-shrink-0 relative">
                                            <div class="absolute inset-y-0 left-0 w-1 bg-black/20 pointer-events-none"></div>
                                            <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div class="relative group flex-1">
                                        <input type="file" name="image_file" @change="coverName = $event.target.files[0]?.name" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-5 text-center group-hover:bg-slate-50 transition-colors" :class="coverName ? 'bg-amber-50 border-amber-400' : ''">
                                            <p class="text-xs font-bold text-slate-600" x-text="coverName ? coverName : 'Changer l\'image de couverture'"></p>
                                        </div>
                                    </div>
                                </div>
                                @error('image_file') <p class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</p> @enderror
                            </div>

                            <!-- Social Proof / Testimonials -->
                            <div x-data="{ fileCount: 0 }">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Avis de lecteurs / Captures d'écran</label>
                                <div class="relative group">
                                    <input type="file" name="testimonials_files[]" @change="fileCount = $event.target.files.length" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-5 text-center group-hover:bg-slate-50 transition-colors" :class="fileCount > 0 ? 'bg-amber-50 border-amber-300' : ''">
                                        <p class="text-xs font-bold text-slate-600" x-text="fileCount > 0 ? fileCount + ' capture(s) sélectionnée(s)' : 'Ajouter de nouvelles captures d\'avis'"></p>
                                    </div>
                                </div>
                                @error('testimonials_files.*') <p class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end gap-4 mt-10 pt-6 border-t border-slate-100">
                        <a href="{{ route('admin.products.index') }}" class="font-bold text-slate-400 hover:text-slate-600 transition-colors px-6 text-sm">Annuler</a>
                        <button type="submit" class="btn-premium-primary min-w-[220px]">
                            Mettre à jour le livre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
