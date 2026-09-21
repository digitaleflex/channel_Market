<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Connexion</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased bg-slate-50 selection:bg-amber-100 selection:text-amber-700 overflow-x-hidden">
        <!-- Decorative Background Elements -->
        <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-amber-500/5 blur-[120px] animate-pulse-slow"></div>
            <div class="absolute top-[40%] -right-[10%] w-[35%] h-[35%] rounded-full bg-orange-500/5 blur-[100px] animate-pulse-slow" style="animation-delay: 2s;"></div>
            <div class="absolute -bottom-[10%] left-[20%] w-[30%] h-[30%] rounded-full bg-amber-500/5 blur-[80px] animate-pulse-slow" style="animation-delay: 4s;"></div>
        </div>

        <div class="min-h-screen flex flex-col items-center justify-center p-6 relative">
            <!-- Back to Home -->
            <div class="absolute top-8 left-8">
                <a href="/" class="flex items-center gap-2 text-slate-500 hover:text-amber-600 font-bold transition-colors group">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Catalogue
                </a>
            </div>

            <div class="w-full sm:max-w-md">
                <div class="mb-10 text-center">
                    <a href="/" class="inline-block group">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200 group-hover:scale-110 transition-transform duration-500 rotate-3">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </a>
                </div>

                <div class="surface p-8 md:p-10 border-slate-200/50 shadow-2xl shadow-slate-200/40 relative">
                    {{ $slot }}
                </div>

                <p class="mt-8 text-center text-slate-500 text-sm font-medium">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                </p>
            </div>
        </div>
    </body>
</html>
