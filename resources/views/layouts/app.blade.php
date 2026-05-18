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
    <body class="font-sans antialiased {{ auth()->user() && auth()->user()->is_admin ? 'premium-dashboard' : 'bg-slate-50' }} selection:bg-amber-500 selection:text-white overflow-x-hidden">
        
        <!-- Decorative Blobs (Premium Ambient Glow) -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
            @if(auth()->user() && auth()->user()->is_admin)
                <div class="blur-blob w-[500px] h-[500px] bg-amber-400/10 top-[-250px] left-[-100px]"></div>
                <div class="blur-blob w-[600px] h-[600px] bg-orange-300/10 bottom-[-200px] right-[-150px] animation-delay-2000"></div>
                <div class="blur-blob w-[450px] h-[450px] bg-rose-200/5 top-[40%] right-[10%] animation-delay-4000"></div>
            @else
                <div class="blur-blob w-[600px] h-[600px] bg-amber-200/20 top-[-300px] left-[-150px]"></div>
                <div class="blur-blob w-[500px] h-[500px] bg-orange-200/20 bottom-[-150px] right-[-150px] animation-delay-2000"></div>
            @endif
        </div>

        <div class="min-h-screen flex flex-col">
            
            @if(auth()->user() && auth()->user()->is_admin)
                <!-- =================================================== -->
                <!--   ULTRA-PREMIUM COLLAPSIBLE VERTICAL SIDEBAR LAYOUT  -->
                <!-- =================================================== -->
                <div x-data="{ 
                     sidebarOpen: false, 
                     sidebarCollapsed: localStorage.getItem('sidebar_collapsed') === 'true' 
                 }" x-effect="localStorage.setItem('sidebar_collapsed', sidebarCollapsed)" class="flex flex-col min-h-screen">
                    
                    <!-- Mobile Top Header Bar -->
                    <header class="lg:hidden h-20 bg-white/80 backdrop-blur-xl border-b border-amber-900/5 sticky top-0 z-40 flex items-center justify-between px-6 shadow-sm shadow-amber-900/5">
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-600 flex items-center justify-center text-white font-black text-lg">
                                C
                            </div>
                            <div>
                                <span class="font-black text-slate-900 text-sm tracking-tight font-display">Channel Market</span>
                                <span class="block text-[8px] font-bold text-amber-600 uppercase tracking-widest -mt-0.5">Admin</span>
                            </div>
                        </a>
                        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </header>

                    <!-- Left Fixed Collapsible Sidebar (Desktop) & Sliding Sidebar (Mobile) -->
                    <aside :class="[
                               sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0', 
                               sidebarCollapsed ? 'lg:w-24' : 'lg:w-72'
                           ]" 
                           class="fixed inset-y-0 left-0 bg-white border-r border-amber-900/5 shadow-lg shadow-amber-950/2 flex flex-col z-50 transition-all duration-300 ease-in-out">
                        
                        <!-- Collapse/Expand Desktop Toggle Button -->
                        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                                class="hidden lg:flex absolute top-8 -right-3.5 w-7 h-7 rounded-full bg-white border border-slate-200 text-slate-500 hover:text-slate-800 shadow-md items-center justify-center cursor-pointer transition-transform duration-300 hover:scale-110 z-50 focus:outline-none"
                                :class="sidebarCollapsed ? 'rotate-180' : ''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                        </button>

                        <!-- Brand/Logo Area -->
                        <div class="h-24 flex items-center border-b border-amber-900/5 transition-all duration-300"
                             :class="sidebarCollapsed ? 'px-6 justify-center' : 'px-8 gap-3'">
                            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30 text-white font-black text-2xl font-display flex-shrink-0">
                                    C
                                </div>
                                <div x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="truncate">
                                    <span class="font-black text-slate-900 text-lg tracking-tight font-display">Channel Market</span>
                                    <span class="block text-[10px] font-bold text-amber-600 uppercase tracking-widest -mt-1">Administration</span>
                                </div>
                            </a>
                        </div>

                        <!-- Sidebar Navigation Menu Links -->
                        <nav class="flex-1 py-8 space-y-1.5 overflow-y-auto transition-all duration-300"
                             :class="sidebarCollapsed ? 'px-3' : 'px-5'">
                            <!-- Catalogue Produits -->
                            <a href="{{ route('admin.products.index') }}" 
                               title="Catalogue Produits"
                               class="nav-sidebar-link transition-all duration-300"
                               :class="sidebarCollapsed ? 'justify-center px-4' : 'px-5 gap-4 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}'"
                               class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="truncate">Catalogue Produits</span>
                            </a>

                            <!-- Commandes & Ventes -->
                            <a href="{{ route('admin.orders.index') }}" 
                               title="Commandes & Ventes"
                               class="nav-sidebar-link transition-all duration-300"
                               :class="sidebarCollapsed ? 'justify-center px-4' : 'px-5 gap-4 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}'"
                               class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="truncate">Commandes & Ventes</span>
                            </a>

                            <!-- Pixels & Tracking -->
                            <a href="{{ route('admin.settings.index') }}" 
                               title="Pixels & Tracking"
                               class="nav-sidebar-link transition-all duration-300"
                               :class="sidebarCollapsed ? 'justify-center px-4' : 'px-5 gap-4 {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}'"
                               class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="truncate">Pixels & Tracking</span>
                            </a>

                            <!-- Évolution & Déploiements -->
                            <a href="{{ route('admin.activity.index') }}" 
                               title="Déploiements & Logs"
                               class="nav-sidebar-link transition-all duration-300"
                               :class="sidebarCollapsed ? 'justify-center px-4' : 'px-5 gap-4 {{ request()->routeIs('admin.activity.*') ? 'active' : '' }}'"
                               class="{{ request()->routeIs('admin.activity.*') ? 'active' : '' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="truncate">Déploiements & Logs</span>
                            </a>
                        </nav>

                        <!-- Profile and Logout Bottom Section -->
                        <div class="p-6 border-t border-amber-900/5 bg-slate-50/50 flex flex-col gap-4 transition-all duration-300">
                            <!-- External Boutique Link -->
                            <a href="{{ route('products.index') }}" target="_blank" 
                               title="Voir Boutique"
                               class="w-full flex items-center rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 hover:border-slate-300 font-bold text-xs transition-all duration-300 shadow-sm"
                               :class="sidebarCollapsed ? 'p-3 justify-center' : 'px-4 py-3 justify-center gap-2'">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Voir Boutique</span>
                            </a>

                            <!-- User Profile Info & Logout -->
                            <div class="flex items-center justify-between gap-3 transition-all duration-300"
                                 :class="sidebarCollapsed ? 'flex-col justify-center' : ''">
                                <div class="truncate" x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                    <span class="block text-sm font-black text-slate-800 leading-tight truncate">{{ auth()->user()->name }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Administrateur</span>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                                    @csrf
                                    <button type="submit" title="Se déconnecter" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 transition-all duration-300 shadow-sm bg-white focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </aside>

                    <!-- Mobile Overlay -->
                    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden" style="display: none;"></div>

                    <!-- Right Side Page Frame -->
                    <div class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out"
                         :class="sidebarCollapsed ? 'lg:pl-24' : 'lg:pl-72'">
                        <!-- Page Header -->
                        @isset($header)
                            <div class="bg-white border-b border-amber-900/5 py-8 px-8 sm:px-12 shadow-sm shadow-slate-100/50">
                                {{ $header }}
                            </div>
                        @endisset

                        <!-- Main Page Slot Content -->
                        <main class="flex-1 py-10 px-8 sm:px-12">
                            {{ $slot }}
                        </main>
                    </div>

                </div>

            @else
                <!-- =================================================== -->
                <!--   STANDARD CLIENT DASHBOARD LAYOUT                  -->
                <!-- =================================================== -->
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
                <main class="flex-1">
                    {{ $slot }}
                </main>
            @endif

        </div>

        @stack('scripts')
    </body>
</html>
