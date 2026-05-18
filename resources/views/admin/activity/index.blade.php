<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-slate-900 tracking-tight font-display">
                    {{ __('Évolution & Diagnostics Système') }}
                </h2>
                <p class="text-slate-600 font-medium mt-1">Suivez en temps réel les changements de configuration, les ventes et le statut des déploiements serveur.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container-app space-y-8">
            
            <!-- Statistiques d'activité -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card-cyber p-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total Actions</div>
                        <div class="text-2xl font-black text-slate-900 mt-1">{{ $activities->total() }}</div>
                    </div>
                </div>

                <div class="card-cyber p-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Déploiements VPS</div>
                        <div class="text-2xl font-black text-slate-900 mt-1">{{ $deployments->total() }}</div>
                    </div>
                </div>

                <div class="card-cyber p-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Alertes Actives</div>
                        <div class="text-2xl font-black text-slate-900 mt-1">E-mail & Webhook</div>
                    </div>
                </div>
            </div>

            <!-- Dashboard principal à onglets -->
            <div class="card-cyber p-8 sm:p-12" x-data="{ tab: '{{ request()->has('deployments_page') ? 'deployments' : 'activities' }}' }">
                
                <!-- En-tête des onglets -->
                <div class="flex border-b border-slate-100 gap-8 mb-8">
                    <button @click="tab = 'activities'" :class="tab === 'activities' ? 'border-amber-500 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600'" class="pb-4 border-b-2 font-black text-sm uppercase tracking-wider transition-all duration-300 focus:outline-none">
                        Journal d'Activité Admin
                    </button>
                    <button @click="tab = 'deployments'" :class="tab === 'deployments' ? 'border-amber-500 text-slate-900' : 'border-transparent text-slate-400 hover:text-slate-600'" class="pb-4 border-b-2 font-black text-sm uppercase tracking-wider transition-all duration-300 focus:outline-none flex items-center gap-2">
                        Suivi des Déploiements
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    </button>
                </div>

                <!-- Onglet 1: Journal d'Activité -->
                <div x-show="tab === 'activities'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight mb-8">Journal Temporel des Évolutions</h3>

                    <div class="relative border-l border-slate-100 ml-4 space-y-10 py-2">
                        @forelse($activities as $activity)
                            @php
                                $badgeColor = match($activity->action) {
                                    'product_created' => 'bg-amber-100 text-amber-700 border-amber-200/50',
                                    'product_updated' => 'bg-sky-100 text-sky-700 border-sky-200/50',
                                    'product_deleted' => 'bg-rose-100 text-rose-700 border-rose-200/50',
                                    'setting_updated' => 'bg-purple-100 text-purple-700 border-purple-200/50',
                                    'order_success'   => 'bg-emerald-100 text-emerald-700 border-emerald-200/50',
                                    default            => 'bg-slate-100 text-slate-700 border-slate-200/50'
                                };

                                $actionLabel = match($activity->action) {
                                    'product_created' => 'Nouveau Produit',
                                    'product_updated' => 'Produit Modifié',
                                    'product_deleted' => 'Produit Supprimé',
                                    'setting_updated' => 'Réglages Système',
                                    'order_success'   => 'Nouvelle Vente',
                                    default            => strtoupper($activity->action)
                                };

                                $icon = match($activity->action) {
                                    'product_created' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>',
                                    'product_updated' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H12v3"></path></svg>',
                                    'product_deleted' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
                                    'setting_updated' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                                    'order_success'   => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                                    default            => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>'
                                };
                            @endphp

                            <div class="relative pl-8 group">
                                <!-- Rond sur la timeline -->
                                <div class="absolute left-[-17px] top-1.5 w-8 h-8 rounded-full border-2 border-white {{ $badgeColor }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                    {!! $icon !!}
                                </div>

                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase border {{ $badgeColor }}">
                                                {{ $actionLabel }}
                                            </span>
                                            <span class="text-sm font-medium text-slate-400">
                                                {{ $activity->created_at->format('d/m/Y à H:i:s') }}
                                            </span>
                                        </div>
                                        <h4 class="font-black text-slate-800 text-lg mt-2 tracking-tight group-hover:text-amber-600 transition-colors">
                                            {{ $activity->description }}
                                        </h4>
                                        
                                        <!-- Auteur et IP -->
                                        <div class="flex items-center gap-3 mt-1.5 text-xs text-slate-500 font-bold">
                                            <span>Par : <span class="text-slate-700 font-extrabold">{{ $activity->user ? $activity->user->name : 'Système Automatique' }}</span></span>
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                                            <span>IP : <code class="bg-slate-50 px-1.5 py-0.5 rounded font-mono border border-slate-100">{{ $activity->ip_address ?? 'Inconnue' }}</code></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Affichage détaillé des modifications de données -->
                                @if($activity->old_values || $activity->new_values)
                                    <div class="mt-4 p-4 rounded-2xl bg-slate-50/50 border border-slate-100 font-mono text-xs max-w-2xl space-y-3" x-data="{ open: false }">
                                        <button @click="open = !open" class="flex items-center gap-1.5 text-slate-500 font-black tracking-wider uppercase text-[10px] hover:text-slate-800 transition-colors">
                                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                            <span>Voir les modifications de données</span>
                                        </button>

                                        <div x-show="open" x-cloak x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-slate-200/50">
                                            @if($activity->old_values)
                                                <div>
                                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Ancienne Valeur</div>
                                                    <ul class="space-y-1 bg-white p-3 rounded-xl border border-slate-200/50 text-slate-600">
                                                        @foreach($activity->old_values as $key => $value)
                                                            <li><span class="text-rose-500 font-bold">{{ $key }} :</span> {{ is_array($value) ? json_encode($value) : $value }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if($activity->new_values)
                                                <div>
                                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nouvelle Valeur</div>
                                                    <ul class="space-y-1 bg-white p-3 rounded-xl border border-slate-200/50 text-slate-600">
                                                        @foreach($activity->new_values as $key => $value)
                                                            <li><span class="text-emerald-600 font-bold">{{ $key }} :</span> {{ is_array($value) ? json_encode($value) : $value }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-20 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="font-bold">Aucune activité enregistrée pour le moment.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($activities->hasPages())
                        <div class="mt-12 pt-8 border-t border-slate-100">
                            {{ $activities->appends(['deployments_page' => $deployments->currentPage()])->links() }}
                        </div>
                    @endif
                </div>

                <!-- Onglet 2: Suivi des Déploiements -->
                <div x-show="tab === 'deployments'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="text-lg font-black text-slate-900 tracking-tight mb-8">Suivi en direct des déploiements du VPS</h3>

                    <div class="relative border-l border-slate-100 ml-4 space-y-10 py-2">
                        @forelse($deployments as $deployment)
                            @php
                                $badgeColor = match($deployment->status) {
                                    'deploying' => 'bg-amber-100 text-amber-700 border-amber-200/50',
                                    'success'   => 'bg-emerald-100 text-emerald-700 border-emerald-200/50',
                                    'failed'    => 'bg-rose-100 text-rose-700 border-rose-200/50',
                                    default     => 'bg-slate-100 text-slate-700 border-slate-200/50'
                                };

                                $statusLabel = match($deployment->status) {
                                    'deploying' => 'En cours...',
                                    'success'   => 'Succès',
                                    'failed'    => 'Échec',
                                    default     => strtoupper($deployment->status)
                                };

                                $icon = match($deployment->status) {
                                    'deploying' => '<svg class="w-4 h-4 animate-spin text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H12v3"></path></svg>',
                                    'success'   => '<svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>',
                                    'failed'    => '<svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>',
                                    default     => '<svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                                };
                            @endphp

                            <div class="relative pl-8 group">
                                <!-- Rond sur la timeline -->
                                <div class="absolute left-[-17px] top-1.5 w-8 h-8 rounded-full border-2 border-white {{ $badgeColor }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300">
                                    {!! $icon !!}
                                </div>

                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase border {{ $badgeColor }}">
                                                {{ $statusLabel }}
                                            </span>
                                            <span class="text-sm font-medium text-slate-400">
                                                Débuté le {{ $deployment->started_at->format('d/m/Y à H:i:s') }}
                                            </span>
                                        </div>
                                        <h4 class="font-black text-slate-800 text-lg mt-2 tracking-tight group-hover:text-amber-600 transition-colors">
                                            {{ $deployment->commit_message ?? 'Déploiement déclenché par GitHub Actions' }}
                                        </h4>
                                        
                                        <!-- Commit SHA et durée -->
                                        <div class="flex items-center gap-3 mt-1.5 text-xs text-slate-500 font-bold">
                                            <span>Commit : <code class="bg-slate-50 px-1.5 py-0.5 rounded font-mono border border-slate-100 text-slate-700 font-extrabold">{{ substr($deployment->commit_sha, 0, 7) }}</code></span>
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                                            <span>Durée : 
                                                <span class="text-slate-700 font-extrabold">
                                                    @if($deployment->duration)
                                                        {{ $deployment->duration }} secondes
                                                    @elseif($deployment->status === 'deploying')
                                                        En cours...
                                                    @else
                                                        Inconnue
                                                    @endif
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p class="font-bold">Aucun déploiement enregistré. Déclenchez une action push pour démarrer le suivi !</p>
                            </div>
                        @endforelse
                    </div>

                    @if($deployments->hasPages())
                        <div class="mt-12 pt-8 border-t border-slate-100">
                            {{ $deployments->appends(['activities_page' => $activities->currentPage()])->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
