@extends('layouts.public')

@section('content')
<div class="container-app relative">
    <!-- Hero Section -->
    <div class="flex flex-col lg:flex-row items-center gap-16 py-12 md:py-24">
        <div class="flex-1 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-100 mb-8 animate-float">
                <span class="flex h-2 w-2 rounded-full bg-amber-600 animate-pulse"></span>
                <span class="text-xs font-bold text-amber-600 uppercase tracking-widest">Nouveau : Scripts Web 2026</span>
            </div>
            <h1 class="text-5xl md:text-8xl font-black tracking-tighter text-slate-900 mb-8 leading-[0.9] lg:-ml-1">
                L'Excellence <br> <span class="gradient-text italic">Digitale</span> <br> à portée de main.
            </h1>
            <p class="text-lg md:text-xl text-slate-500 max-w-xl mb-12 leading-relaxed font-medium">
                Chanel Market est la place de marché de référence pour les créateurs exigeants. Découvrez des ressources premium conçues pour propulser votre succès.
            </p>
            <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                <a href="{{ route('products.index') }}" class="btn-premium-primary group">
                    Explorer la Boutique
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
                <a href="#features" class="btn-premium-secondary">Pourquoi nous ?</a>
            </div>
            
            <div class="mt-16 flex items-center justify-center lg:justify-start gap-8 opacity-50">
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-slate-900">5k+</span>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Ventes</span>
                </div>
                <div class="w-px h-10 bg-slate-200"></div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-slate-900">99%</span>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Satisfaction</span>
                </div>
            </div>
        </div>

        <div class="flex-1 relative">
            <!-- Visual Element -->
            <div class="relative w-full aspect-square max-w-xl mx-auto">
                <div class="absolute inset-0 bg-gradient-to-tr from-amber-500 to-orange-600 rounded-[3rem] rotate-6 opacity-10 animate-pulse"></div>
                <div class="absolute inset-0 bg-white rounded-[3rem] shadow-2xl border border-slate-100 overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1633356122544-f134324a6cee?q=80&w=2070&auto=format&fit=crop" alt="Digital Product" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="glass p-6 rounded-2xl border-white/20">
                            <p class="text-white text-sm font-bold mb-1 italic">Produit Vedette</p>
                            <h3 class="text-white text-xl font-black tracking-tight">SaaS Starter Kit Pro</h3>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Tags -->
                <div class="absolute -top-6 -right-6 glass p-4 rounded-2xl shadow-2xl border-white animate-float">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Paiement</p>
                            <p class="text-sm font-black text-slate-900">100% Sécurisé</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 border-t border-slate-100">
        <div class="text-center mb-20">
            <h2 class="text-3xl md:text-5xl font-black tracking-tighter text-slate-900 mb-4">Une expérience d'achat <br> <span class="text-amber-600">réinventée</span>.</h2>
            <p class="text-slate-500 font-medium">Pourquoi choisir Chanel Market pour vos ressources numériques ?</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="surface p-10 hover:-translate-y-2">
                <div class="w-14 h-14 rounded-2xl bg-amber-600 flex items-center justify-center text-white mb-8 shadow-lg shadow-amber-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Vitesse Instantanée</h3>
                <p class="text-slate-500 text-sm leading-relaxed font-medium">
                    Pas d'attente. Vos produits sont disponibles en téléchargement immédiatement après la validation de votre paiement.
                </p>
            </div>

            <div class="surface p-10 hover:-translate-y-2">
                <div class="w-14 h-14 rounded-2xl bg-orange-600 flex items-center justify-center text-white mb-8 shadow-lg shadow-orange-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Qualité Certifiée</h3>
                <p class="text-slate-500 text-sm leading-relaxed font-medium">
                    Chaque ressource est rigoureusement testée par notre équipe pour garantir une performance optimale et sans bug.
                </p>
            </div>

            <div class="surface p-10 hover:-translate-y-2">
                <div class="w-14 h-14 rounded-2xl bg-emerald-600 flex items-center justify-center text-white mb-8 shadow-lg shadow-emerald-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Prix Juste</h3>
                <p class="text-slate-500 text-sm leading-relaxed font-medium">
                    Nous croyons en la démocratisation de la technologie. Des outils professionnels à des tarifs accessibles à tous.
                </p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-24">
        <div class="bg-slate-900 rounded-[3.5rem] p-12 md:p-24 text-center relative overflow-hidden shadow-2xl">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_0%,rgba(245,158,11,0.15),transparent_70%)]"></div>
            <div class="relative z-10">
                <h2 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-8 leading-tight">
                    Prêt à transformer <br> vos <span class="text-amber-400 italic">projets</span> ?
                </h2>
                <p class="text-slate-400 text-lg md:text-xl max-w-xl mx-auto mb-12 font-medium">
                    Rejoignez des milliers de créateurs et développeurs qui font confiance à Chanel Market pour leurs besoins digitaux.
                </p>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="{{ route('products.index') }}" class="btn-premium-primary !py-4 !px-10 text-lg">Voir le catalogue</a>
                    <a href="{{ route('register') }}" class="btn-premium bg-white/5 text-white border border-white/10 hover:bg-white/10 !py-4 !px-10 text-lg">S'inscrire gratuitement</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
