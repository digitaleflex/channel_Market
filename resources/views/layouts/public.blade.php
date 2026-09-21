<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-seo :title="$title ?? null" :description="$description ?? null" :image="$ogImage ?? null" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('components.tracking')
</head>
<body class="flex flex-col min-h-screen selection:bg-[#ffcd00] selection:text-[#192230] bg-[#F7F8FA] overflow-x-hidden" x-data="{ mobileMenuOpen: false }">
    <!-- Noguchi Architectural Ambient Accents -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="blur-blob w-[600px] h-[600px] bg-[#ffcd00]/10 top-[-250px] right-[-100px]"></div>
        <div class="blur-blob w-[500px] h-[500px] bg-slate-200/40 bottom-[-150px] left-[-100px]"></div>
    </div>

    <nav id="main-nav" class="fixed w-full z-50 top-0 transition-all duration-500 bg-white/85 backdrop-blur-xl border-b border-slate-200/80">
        <div class="container-app">
            <div class="flex justify-between h-20 items-center nav-container transition-all duration-500">
                <div class="flex items-center">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3.5 group">
                        <div class="w-11 h-11 rounded-2xl bg-[#F7F8FA] border border-slate-200 flex items-center justify-center shadow-sm group-hover:scale-105 group-hover:border-[#ffcd00] transition-all duration-300">
                            <!-- Noguchi Split Duotone Circle -->
                            <div class="w-6 h-6 rounded-full overflow-hidden flex border border-[#ffcd00]/30 shadow-sm">
                                <div class="w-1/2 h-full bg-[#192230]"></div>
                                <div class="w-1/2 h-full bg-[#ffcd00]"></div>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black tracking-tight text-[#192230] leading-none font-display flex items-center gap-1.5">
                                All_Books
                                <span class="w-2 h-2 rounded-full bg-[#ffcd00] inline-block shadow-sm"></span>
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-[0.25em] text-[#3d474e] mt-1">Librairie Digitale</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('products.index') }}" class="text-sm font-bold text-[#192230] hover:text-[#ffcd00] transition-colors py-2">Catalogue</a>
                    <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-[#3d474e] hover:text-[#192230] transition-colors py-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#ffcd00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Ma Bibliothèque
                    </a>
                    
                    <div class="h-6 w-px bg-slate-200"></div>

                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-bold text-[#3d474e] hover:text-[#192230] transition-colors">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-premium-primary !py-2.5 !px-6 !text-xs !rounded-xl">Rejoindre</a>
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-[#ffcd00] hover:text-[#192230] text-[#192230] text-xs font-bold transition-all">
                                <span class="w-2 h-2 rounded-full bg-[#ffcd00]"></span>
                                Mon Espace
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-slate-400 hover:text-rose-600 transition-colors flex items-center gap-1.5 group">
                                    <span>Quitter</span>
                                    <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = true" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-600 shadow-sm active:scale-95 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-full"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-full"
         class="fixed inset-0 z-[60] bg-white md:hidden"
         style="display: none;">
        
        <div class="p-6 flex flex-col h-full bg-white text-[#192230]">
            <div class="flex justify-between items-center mb-12">
                <a href="{{ route('products.index') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#F7F8FA] border border-[#ffcd00] flex items-center justify-center text-[#192230] shadow-sm">
                        <div class="w-5 h-5 rounded-full overflow-hidden flex border border-[#ffcd00]/40">
                            <div class="w-1/2 h-full bg-[#192230]"></div>
                            <div class="w-1/2 h-full bg-[#ffcd00]"></div>
                        </div>
                    </div>
                    <span class="text-xl font-black tracking-tight text-[#192230] font-display">All_Books<span class="text-[#ffcd00]">.</span></span>
                </a>
                <button @click="mobileMenuOpen = false" class="w-12 h-12 flex items-center justify-center rounded-xl bg-slate-100 text-[#3d474e] hover:text-[#192230]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex flex-col gap-6 flex-grow">
                <a href="{{ route('products.index') }}" class="text-2xl font-black text-[#192230] hover:text-[#3d474e] transition-colors">Catalogue</a>
                <a href="{{ url('/dashboard') }}" class="text-2xl font-black text-[#3d474e] hover:text-[#192230] transition-colors">Ma Bibliothèque</a>
                
                <div class="h-px bg-slate-200 my-4"></div>
                
                @guest
                    <a href="{{ route('login') }}" class="text-lg font-bold text-[#3d474e] hover:text-[#192230]">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-premium-primary justify-center text-base !py-4">Rejoindre la Librairie</a>
                @else
                    <a href="{{ url('/dashboard') }}" class="text-lg font-bold text-[#192230]">Mon Espace Lecteur</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-lg font-bold text-rose-600 hover:text-rose-700">Déconnexion</button>
                    </form>
                @endguest
            </div>

            <div class="pt-8 border-t border-slate-200">
                <p class="text-slate-500 text-xs font-medium">&copy; {{ date('Y') }} All_Books. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <main class="flex-grow pt-28 pb-20">
        @if (session('success'))
            <div class="container-app mb-8">
                <div class="glass border-emerald-500/30 bg-emerald-500/10 text-emerald-900 px-6 py-4 rounded-2xl flex items-center gap-3" role="alert">
                    <div class="w-8 h-8 rounded-full bg-[#ffcd00] text-[#192230] flex items-center justify-center shrink-0 font-black">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Architectural Light Footer (No #192230 Background) -->
    <footer class="bg-white text-[#192230] border-t border-slate-200/90 py-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#ffcd00]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="container-app relative">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
                <div class="md:col-span-5">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3.5 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#F7F8FA] border border-[#ffcd00] flex items-center justify-center text-[#192230] shadow-sm">
                            <div class="w-6 h-6 rounded-full overflow-hidden flex border border-[#ffcd00]/40">
                                <div class="w-1/2 h-full bg-[#192230]"></div>
                                <div class="w-1/2 h-full bg-[#ffcd00]"></div>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black tracking-tight text-[#192230] font-display">All_Books<span class="text-[#ffcd00]">.</span></span>
                            <span class="text-[9px] font-black uppercase tracking-[0.25em] text-[#3d474e] mt-0.5">Édition & Librairie Digitale</span>
                        </div>
                    </a>
                    <p class="text-[#3d474e] max-w-sm leading-relaxed mb-8 text-sm font-normal">
                        La librairie numérique d'excellence. E-books de référence, guides stratégiques et savoirs actionnables. Téléchargement immédiat en formats PDF et ePub.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="https://www.facebook.com/profile.php?id=61588294455790" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-[#3d474e] hover:text-[#192230] hover:bg-[#ffcd00] transition-all duration-300" title="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <div class="text-xs text-slate-500 font-medium ml-2">
                            Paiements 100% sécurisés par Mobile Money & Cartes
                        </div>
                    </div>
                </div>

                <div class="md:col-span-3 md:col-start-7">
                    <h4 class="font-black text-[#192230] mb-6 uppercase text-xs tracking-[0.2em] font-display">Rayons & Navigation</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('products.index') }}" class="text-[#3d474e] hover:text-[#192230] transition-colors text-sm font-medium">Tous les ouvrages</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="text-[#3d474e] hover:text-[#192230] transition-colors text-sm font-medium">Ma Bibliothèque</a></li>
                        <li><a href="{{ route('login') }}" class="text-[#3d474e] hover:text-[#192230] transition-colors text-sm font-medium">Espace Lecteur</a></li>
                    </ul>
                </div>

                
            </div>
            
            <div class="mt-16 pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-500 text-xs font-medium">&copy; {{ date('Y') }} All_Books. Atelier de Publication Numérique. Tous droits réservés.</p>
                <div class="flex items-center gap-6">
                    <a href="https://www.linkedin.com/in/elfridamelvinefleurs%C3%A8djro-yemadje/" target="_blank" class="text-[10px] font-bold text-slate-400 hover:text-[#192230] uppercase tracking-widest transition-colors">Développé par MelDev</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('main-nav');
            const container = nav.querySelector('.nav-container');
            if (window.scrollY > 20) {
                nav.classList.add('bg-white/90', 'backdrop-blur-xl', 'border-b', 'border-slate-100', 'shadow-sm');
                container.classList.remove('h-24');
                container.classList.add('h-20');
            } else {
                nav.classList.remove('bg-white/90', 'backdrop-blur-xl', 'border-b', 'border-slate-100', 'shadow-sm');
                container.classList.add('h-24');
                container.classList.remove('h-20');
            }
        });
    </script>
</body>
</html>
