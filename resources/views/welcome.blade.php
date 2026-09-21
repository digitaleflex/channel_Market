@extends('layouts.public')

@section('content')
<div class="container-app relative">
    <!-- Hero Section -->
    <div class="flex flex-col lg:flex-row items-center gap-16 py-12 md:py-24">
        <div class="flex-1 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-100 mb-8 animate-float">
                <span class="flex h-2 w-2 rounded-full bg-amber-600 animate-pulse"></span>
                <span class="text-xs font-bold text-amber-600 uppercase tracking-widest">Nouveau : Collection E-books 2026</span>
            </div>
            <h1 class="text-5xl md:text-8xl font-black tracking-tighter text-slate-900 mb-8 leading-[0.9] lg:-ml-1 font-display">
                L'Excellence <br> <span class="gradient-text italic">Littéraire</span> <br> à portée de main.
            </h1>
            <p class="text-lg md:text-xl text-slate-600 max-w-xl mb-12 leading-relaxed font-medium">
                All_Books est la librairie digitale de référence pour les esprits curieux et ambitieux. Découvrez des livres numériques et guides d'experts conçus pour enrichir votre savoir.
            </p>
            <div class="flex flex-wrap justify-center lg:justify-start gap-4">
                <a href="{{ route('products.index') }}#catalogue" class="btn-premium-primary group">
                    Explorer le Catalogue
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
                <a href="#features" class="btn-premium-secondary">Pourquoi All_Books ?</a>
            </div>
            
            <div class="mt-16 flex items-center justify-center lg:justify-start gap-8 opacity-60">
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-slate-900">5k+</span>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Lecteurs satisfaits</span>
                </div>
                <div class="w-px h-10 bg-slate-200"></div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-slate-900">100%</span>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Téléchargement instantané</span>
                </div>
            </div>
        </div>

        <div class="flex-1 relative">
            <!-- Visual Element -->
            <div class="relative w-full aspect-[3/4] max-w-md mx-auto">
                <div class="absolute inset-0 bg-gradient-to-tr from-amber-500 to-orange-600 rounded-[3rem] rotate-6 opacity-15 animate-pulse"></div>
                <div class="absolute inset-0 bg-white rounded-[3rem] shadow-2xl border border-slate-100 overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=1000&auto=format&fit=crop" alt="Livre en vedette" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-8 left-8 right-8">
                        <div class="glass p-6 rounded-2xl border-white/20">
                            <p class="text-amber-400 text-xs font-bold uppercase tracking-widest mb-1">Ouvrage Vedette</p>
                            <h3 class="text-white text-xl font-black tracking-tight font-display">Père Riche, Père Pauvre</h3>
                            <p class="text-slate-300 text-xs mt-1">Robert Kiyosaki • Format PDF & ePub</p>
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
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Paiement</p>
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
            <h2 class="text-3xl md:text-5xl font-black tracking-tighter text-slate-900 mb-4 font-display">Une expérience de lecture <br> <span class="text-amber-600">réinventée</span>.</h2>
            <p class="text-slate-600 font-medium">Pourquoi choisir All_Books pour vos livres numériques ?</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="surface p-10 hover:-translate-y-2">
                <div class="w-14 h-14 rounded-2xl bg-amber-600 flex items-center justify-center text-white mb-8 shadow-lg shadow-amber-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Accès Instantané</h3>
                <p class="text-slate-600 text-sm leading-relaxed font-medium">
                    Pas d'attente de livraison. Vos livres numériques sont disponibles au téléchargement immédiatement après votre règlement sécurisé.
                </p>
            </div>

            <div class="surface p-10 hover:-translate-y-2">
                <div class="w-14 h-14 rounded-2xl bg-orange-600 flex items-center justify-center text-white mb-8 shadow-lg shadow-orange-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Qualité Éditoriale</h3>
                <p class="text-slate-600 text-sm leading-relaxed font-medium">
                    Chaque ouvrage est vérifié, mis en page avec soin et optimisé pour une lecture fluide sur smartphone, liseuse, tablette ou ordinateur.
                </p>
            </div>

            <div class="surface p-10 hover:-translate-y-2">
                <div class="w-14 h-14 rounded-2xl bg-emerald-600 flex items-center justify-center text-white mb-8 shadow-lg shadow-emerald-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Tarifs Accessibles</h3>
                <p class="text-slate-600 text-sm leading-relaxed font-medium">
                    Nous croyons en la diffusion universelle de la connaissance. Des ouvrages de valeur à des tarifs attractifs avec paiement Mobile Money et Cartes.
                </p>
            </div>
        </div>
    </div>


</div>
@endsection
