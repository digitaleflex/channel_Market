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
        @stack('scripts')
    </body>
</html>
