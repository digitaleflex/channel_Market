<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-900 tracking-tight font-display">
                    {{ __('Configuration Marketing') }}
                </h2>
                <p class="text-slate-600 font-medium mt-1">Gérez vos pixels de tracking et outils d'analyse.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-app">
            
            @if (session('success'))
                <div class="glass border-green-200 text-green-700 px-6 py-4 rounded-[1.5rem] mb-8 flex items-center gap-3 animate-shake">
                    <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="max-w-2xl">
                <div class="card-premium p-8">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-8">
                            <!-- Meta Pixel -->
                            <div>
                                <label for="meta_pixel_id" class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-3">
                                    Meta Pixel ID (Facebook)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </div>
                                    <input type="text" name="meta_pixel_id" id="meta_pixel_id" 
                                           class="input-premium pl-12" 
                                           placeholder="Ex: 1382795156949761"
                                           value="{{ old('meta_pixel_id', $settings['META_PIXEL_ID'] ?? '') }}">
                                </div>
                                <p class="mt-2 text-xs text-slate-500 font-medium italic">Utilisé pour le retargeting et le suivi des conversions sur Meta.</p>
                            </div>

                            <!-- Google Analytics -->
                            <div>
                                <label for="google_analytics_id" class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-3">
                                    Google Analytics ID (G-XXXXX)
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    </div>
                                    <input type="text" name="google_analytics_id" id="google_analytics_id" 
                                           class="input-premium pl-12" 
                                           placeholder="Ex: G-XXXXXXXXXX"
                                           value="{{ old('google_analytics_id', $settings['GOOGLE_ANALYTICS_ID'] ?? '') }}">
                                </div>
                                <p class="mt-2 text-xs text-slate-500 font-medium italic">Suivez le trafic et le comportement des utilisateurs via Google Analytics 4.</p>
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="btn-premium-primary w-full justify-center py-4">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
