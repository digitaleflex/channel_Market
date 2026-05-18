<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <x-seo :title="$title ?? null" :description="$description ?? null" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')

        @include('components.tracking')
    </head>
    <body class="font-sans antialiased bg-slate-50 selection:bg-amber-500 selection:text-white overflow-x-hidden">
        
        @if(auth()->user() && auth()->user()->is_admin)
            <!-- ========================================== -->
            <!--   IMMERSIVE PREMIUM ADMIN SIDEBAR LAYOUT   -->
            <!-- ========================================== -->
            <div class="min-h-screen flex flex-col lg:flex-row" x-data="{ sidebarOpen: false }">
                
                <!-- Sidebar Background Overlay (mobile) -->
                <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                <!-- Sidebar Container -->
                <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 flex flex-col w-72 bg-slate-900 border-r border-slate-800 text-slate-300 lg:static lg:translate-x-0 transition-transform duration-300 ease-in-out shrink-0">
                    <!-- Brand logo / header -->
                    <div class="h-20 flex items-center px-8 border-b border-slate-800 bg-slate-950/40">
                        <a href="{{ route('products.index') }}" class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/20 text-white font-black text-xl">
                                C
                            </div>
                            <div>
                                <span class="font-black text-white text-lg tracking-tight font-display">Channel Market</span>
                                <span class="block text-[10px] font-bold text-amber-500 uppercase tracking-widest -mt-1">Admin Panel</span>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation links -->
                    <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                        <!-- Products -->
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold transition-all duration-300 {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/10' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span>Catalogue Produits</span>
                        </a>

                        <!-- Orders -->
                        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold transition-all duration-300 {{ request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/10' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span>Commandes & Ventes</span>
                        </a>

                        <!-- Settings -->
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold transition-all duration-300 {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/10' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Pixels & Tracking</span>
                        </a>

                        <!-- Evolution -->
                        <a href="{{ route('admin.activity.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl font-bold transition-all duration-300 {{ request()->routeIs('admin.activity.*') ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/10' : 'hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>Évolution & Déploiements</span>
                        </a>
                    </nav>

                    <!-- Sidebar Footer -->
                    <div class="p-6 border-t border-slate-800 bg-slate-950/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-black text-slate-300">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="block text-sm font-bold text-white truncate">{{ auth()->user()->name }}</span>
                                <span class="block text-[10px] text-slate-500 truncate">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                        
                        <!-- Deconnexion -->
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-rose-400 hover:border-rose-900/30 hover:bg-rose-950/20 transition-all duration-300 text-xs font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span>Se déconnecter</span>
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
                    
                    <!-- Decorative Blobs (reduced for admin to keep UI clean and highly readable) -->
                    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
                        <div class="blur-blob w-[400px] h-[400px] bg-amber-100/10 top-[-100px] right-[-100px]"></div>
                    </div>

                    <!-- Top Navbar (header bar) -->
                    <header class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-8 sticky top-0 z-30 shadow-sm shadow-slate-100/30">
                        <div class="flex items-center gap-4">
                            <!-- Mobile sidebar toggle -->
                            <button @click="sidebarOpen = true" class="p-2 -ml-2 text-slate-500 hover:text-slate-800 lg:hidden focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>
                            
                            @isset($header)
                                <div class="hidden sm:block">
                                    {{ $header }}
                                </div>
                            @endisset
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <!-- External Store Link -->
                            <a href="{{ route('products.index') }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold text-xs transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                <span>Voir la Boutique</span>
                            </a>
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="flex-1 py-12">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @else
            <!-- ========================================== -->
            <!--   STANDARD USER OR CLIENT DASHBOARD LAYOUT  -->
            <!-- ========================================== -->
            <!-- Decorative Blobs -->
            <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
                <div class="blur-blob w-[600px] h-[600px] bg-amber-200/30 top-[-300px] left-[-150px]"></div>
                <div class="blur-blob w-[500px] h-[500px] bg-orange-200/30 bottom-[-150px] right-[-150px] animation-delay-2000"></div>
            </div>

            <div class="min-h-screen">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/60 backdrop-blur-xl border-b border-slate-100/50 sticky top-16 z-30">
                        <div class="container-app py-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        @endif

        @stack('scripts')
    </body>
</html>
