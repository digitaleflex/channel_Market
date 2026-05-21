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
<body class="flex flex-col min-h-screen selection:bg-amber-500 selection:text-white bg-slate-50 overflow-x-hidden" x-data="{ mobileMenuOpen: false }">
    <!-- Decorative Blobs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
        <div class="blur-blob w-[600px] h-[600px] bg-amber-200/40 top-[-300px] left-[-150px]"></div>
        <div class="blur-blob w-[500px] h-[500px] bg-orange-200/40 bottom-[-150px] right-[-150px] animation-delay-2000"></div>
    </div>

    <nav id="main-nav" class="fixed w-full z-50 top-0 transition-all duration-500">
        <div class="container-app">
            <div class="flex justify-between h-24 items-center nav-container transition-all duration-500">
                <div class="flex items-center">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 group">
                        <div class="w-12 h-12 rounded-xl overflow-hidden shadow-lg shadow-amber-500/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <img src="{{ asset('img/logo.jpg') }}" alt="Channel Market" class="w-full h-full object-cover">
                        </div>
                        <span class="text-2xl font-black tracking-tighter gradient-text">Channel Market</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-10">
                    <a href="{{ route('products.index') }}" class="nav-link">Boutique</a>
                    <a href="{{ url('/dashboard') }}" class="nav-link flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Mes achats
                    </a>
                    
                    <div class="h-6 w-px bg-slate-200"></div>

                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-amber-600 transition-colors">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-premium-primary !py-2.5 !px-6">Rejoindre</a>
                    @else
                        <div class="flex items-center gap-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm font-bold text-slate-500 hover:text-rose-500 transition-colors flex items-center gap-2 group">
                                    <span>Déconnexion</span>
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
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
        
        <div class="p-6 flex flex-col h-full">
            <div class="flex justify-between items-center mb-12">
                <a href="{{ route('products.index') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl overflow-hidden shadow-lg shadow-amber-200">
                        <img src="{{ asset('img/logo.jpg') }}" alt="Channel Market" class="w-full h-full object-cover">
                    </div>
                    <span class="text-xl font-black tracking-tighter text-slate-900">Channel Market</span>
                </a>
                <button @click="mobileMenuOpen = false" class="w-12 h-12 flex items-center justify-center rounded-xl bg-slate-50 text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex flex-col gap-6 flex-grow">
                <a href="{{ route('products.index') }}" class="text-3xl font-black text-slate-900 hover:text-amber-600 transition-colors">Boutique</a>
                <a href="{{ url('/dashboard') }}" class="text-3xl font-black text-slate-900 hover:text-amber-600 transition-colors">Mes achats</a>
                
                <div class="h-px bg-slate-100 my-4"></div>
                
                @guest
                    <a href="{{ route('login') }}" class="text-xl font-bold text-slate-600">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-premium-primary justify-center text-xl !py-5">Rejoindre l'aventure</a>
                @else
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xl font-bold text-rose-500">Déconnexion</button>
                    </form>
                @endguest
            </div>

            <div class="pt-8 border-t border-slate-100">
                <p class="text-slate-500 text-sm font-medium">&copy; {{ date('Y') }} Channel Market. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <main class="flex-grow pt-32 pb-20">
        @if (session('success'))
            <div class="container-app mb-8">
                <div class="glass border-emerald-200/50 bg-emerald-50/80 text-emerald-800 px-6 py-4 rounded-2xl flex items-center gap-3 animate-float" role="alert">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-100 py-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-amber-50/30 -skew-x-12 translate-x-1/2"></div>
        <div class="container-app relative">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
                <div class="md:col-span-5">
                    <a href="#" class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 rounded-xl overflow-hidden shadow-lg shadow-slate-200">
                            <img src="{{ asset('img/logo.jpg') }}" alt="Channel Market" class="w-full h-full object-cover">
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-slate-900">Channel Market</span>
                    </a>
                    <p class="text-slate-600 max-w-sm leading-relaxed mb-8 text-lg font-medium">
                        La plateforme numéro un pour vos produits digitaux premium. Accédez à des ressources exclusives instantanément.
                    </p>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/profile.php?id=61588294455790" target="_blank" rel="noopener noreferrer" class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-amber-600 hover:text-white transition-all duration-300" title="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    </div>
                </div>

                <div class="md:col-span-2 md:col-start-8">
                    <h4 class="font-black text-slate-900 mb-8 uppercase text-xs tracking-[0.2em]">Navigation</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('products.index') }}" class="text-slate-600 hover:text-amber-600 transition-colors text-sm font-bold">Boutique</a></li>
                        <li><a href="{{ url('/dashboard') }}" class="text-slate-600 hover:text-amber-600 transition-colors text-sm font-bold">Mes achats</a></li>
                        <li><a href="{{ route('login') }}" class="text-slate-600 hover:text-amber-600 transition-colors text-sm font-bold">Connexion</a></li>
                    </ul>
                </div>

                <div class="md:col-span-3">
                    <h4 class="font-black text-slate-900 mb-8 uppercase text-xs tracking-[0.2em]">Support</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-600 hover:text-amber-600 transition-colors text-sm font-bold">Centre d'aide</a></li>
                        <li><a href="" class="text-slate-600 hover:text-amber-600 transition-colors text-sm font-bold">+22969573488</a></li>
                        <div class="mb-8 space-y-3 text-slate-600 text-sm font-medium">
                        <li><a href="mailto:mahougnonbalaam@gmail.com" class="text-slate-600 hover:text-amber-600 transition-colors text-sm font-bold">mahougnonbalaam@gmail.com</a></li>
                        </div>
                    </ul>
                </div>
            </div>
            
            <div class="mt-20 pt-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-500 text-sm font-bold tracking-tight">&copy; {{ date('Y') }} Channel Market. Built with Excellence.</p>
                <div class="flex items-center gap-8">
                    <span> <a href="https://www.linkedin.com/in/elfridamelvinefleurs%C3%A8djro-yemadje/"  class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic" >Developped by Elfrida YEMADJE </a> </span>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Premium Marketplace</span>
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
