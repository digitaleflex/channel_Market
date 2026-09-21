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
                    {{ __('Ajouter un Livre Numérique') }}
                </h2>
                <p class="text-slate-500 font-medium mt-1">Publiez un nouvel ouvrage ou e-book dans la librairie All_Books.</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn-premium-secondary !py-2.5">
                Retour au catalogue
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12" x-data="{ productType: 'file', sampleType: 'none' }">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <!-- Left Column: Book Details -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Titre du livre *</label>
                                <input type="text" name="title" required class="input-premium" value="{{ old('title') }}" placeholder="Ex: Père Riche, Père Pauvre">
                                @error('title') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Auteur *</label>
                                    <input type="text" name="author" required class="input-premium" value="{{ old('author') }}" placeholder="Ex: Robert Kiyosaki">
                                    @error('author') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Catégorie / Thème</label>
                                    <input type="text" name="category" list="categories-list" class="input-premium" value="{{ old('category') }}" placeholder="Ex: Développement Personnel">
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
                                        <option value="PDF" {{ old('format') === 'PDF' ? 'selected' : '' }}>PDF</option>
                                        <option value="EPUB" {{ old('format') === 'EPUB' ? 'selected' : '' }}>EPUB</option>
                                        <option value="PDF + EPUB" {{ old('format') === 'PDF + EPUB' ? 'selected' : '' }}>PDF + EPUB</option>
                                        <option value="Audiobook (MP3)" {{ old('format') === 'Audiobook (MP3)' ? 'selected' : '' }}>Audiobook (MP3)</option>
                                        <option value="Kindle / MOBI" {{ old('format') === 'Kindle / MOBI' ? 'selected' : '' }}>Kindle / MOBI</option>
                                    </select>
                                    @error('format') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Nb Pages</label>
                                    <input type="number" name="pages_count" class="input-premium" value="{{ old('pages_count') }}" placeholder="Ex: 320" min="1">
                                    @error('pages_count') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Langue</label>
                                    <input type="text" name="language" class="input-premium" value="{{ old('language', 'Français') }}" placeholder="Français">
                                    @error('language') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Année de parution</label>
                                    <input type="number" name="publication_year" class="input-premium" value="{{ old('publication_year', date('Y')) }}" placeholder="2026" min="1800" max="2100">
                                    @error('publication_year') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">ISBN / Référence</label>
                                    <input type="text" name="isbn" class="input-premium" value="{{ old('isbn') }}" placeholder="978-2-...">
                                    @error('isbn') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Prix de vente (CFA) *</label>
                                    <div class="relative">
                                        <input type="number" step="1" name="price" required class="input-premium pl-14" value="{{ old('price') }}" placeholder="2500">
                                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">CFA</div>
                                    </div>
                                    @error('price') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Chariow Product ID</label>
                                    <input type="text" name="chariow_product_id" class="input-premium" value="{{ old('chariow_product_id') }}" placeholder="Ex: prd_12345">
                                    @error('chariow_product_id') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Résumé complet de l'ouvrage *</label>
                                <input id="description" type="hidden" name="description" value="{{ old('description') }}">
                                <trix-editor input="description" class="trix-content" placeholder="Présentez le livre, son auteur et ce que le lecteur va y découvrir..."></trix-editor>
                                @error('description') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Right Column: Files, Sample, Cover & Testimonials -->
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
                                        <input type="file" name="file" @change="fileName = $event.target.files[0]?.name" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" :required="productType === 'file'">
                                        <div class="border-2 border-dashed border-amber-300 rounded-2xl p-6 text-center group-hover:bg-white transition-colors" :class="fileName ? 'bg-white border-emerald-500' : ''">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2" :class="fileName ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            </div>
                                            <p class="text-xs font-bold text-slate-700" x-text="fileName ? fileName : 'Sélectionner le fichier du livre complet'"></p>
                                        </div>
                                    </div>
                                    @error('file') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>

                                <div x-show="productType === 'link'" x-cloak>
                                    <input type="url" name="drive_link" class="input-premium" value="{{ old('drive_link') }}" placeholder="https://drive.google.com/file/d/..." :required="productType === 'link'">
                                    <p class="text-[10px] text-slate-400 mt-1 font-bold">Le lien doit être accessible aux personnes disposant du lien.</p>
                                    @error('drive_link') <span class="text-rose-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Free Sample Excerpt -->
                            <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-700 mb-2">Extrait gratuit (« Feuilleter le livre »)</label>
                                <p class="text-xs text-slate-500 mb-3">Permet aux visiteurs de lire les premières pages avant d'acheter.</p>
                                
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
                                            <p class="text-xs font-bold text-slate-600" x-text="sampleName ? sampleName : 'Sélectionner l\'extrait PDF'"></p>
                                        </div>
                                    </div>
                                    @error('sample_file_upload') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div x-show="sampleType === 'link'" x-cloak>
                                    <input type="url" name="sample_link" class="input-premium" value="{{ old('sample_link') }}" placeholder="https://drive.google.com/.../preview">
                                    @error('sample_link') <span class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Book Cover Image (Mandatory, Vertical recommended) -->
                            <div x-data="{ coverName: '' }">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Couverture du livre (Format vertical 2:3 ou 3:4) *</label>
                                <div class="relative group">
                                    <input type="file" name="image_file" @change="coverName = $event.target.files[0]?.name" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center group-hover:bg-slate-50 group-hover:border-slate-300 transition-colors" :class="coverName ? 'bg-amber-50 border-amber-400' : ''">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-2 text-slate-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-xs font-bold text-slate-600" x-text="coverName ? coverName : 'Télécharger l\'image de couverture du livre'"></p>
                                        <span class="text-[10px] text-slate-400 block mt-1">Recommandé : format vertical (ex: 600x800 ou 800x1200 px)</span>
                                    </div>
                                </div>
                                @error('image_file') <p class="text-rose-500 text-xs font-bold mt-1 block">{{ $message }}</p> @enderror
                            </div>

                            <!-- Social Proof / Reader Testimonials -->
                            <div x-data="{ fileCount: 0 }">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Avis de lecteurs / Captures (Optionnel)</label>
                                <div class="relative group">
                                    <input type="file" name="testimonials_files[]" @change="fileCount = $event.target.files.length" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="border-2 border-dashed border-slate-200 rounded-2xl p-5 text-center group-hover:bg-slate-50 transition-colors" :class="fileCount > 0 ? 'bg-amber-50 border-amber-300' : ''">
                                        <p class="text-xs font-bold text-slate-600" x-text="fileCount > 0 ? fileCount + ' capture(s) sélectionnée(s)' : 'Ajouter des captures d\'avis de lecteurs'"></p>
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
                            Publier le livre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
