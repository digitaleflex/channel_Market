<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Bon retour !</h2>
        <p class="text-slate-500 font-medium">Connectez-vous pour accéder à vos produits.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email</label>
            <input id="email" 
                   class="input-premium @error('email') border-red-500 @enderror" 
                   type="email" 
                   name="email" 
                   :value="old('email')" 
                   placeholder="votre@email.com"
                   required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <div class="flex items-center justify-between mb-2 ml-1">
                <label for="password" class="text-sm font-bold text-slate-700">Mot de passe</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-amber-600 hover:text-amber-700 transition" href="{{ route('password.request') }}">
                        Oublié ?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input id="password" 
                       class="input-premium @error('password') border-red-500 @enderror !pr-12"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       placeholder="••••••••"
                       required autocomplete="current-password" />
                <button type="button" 
                        @click="show = !show" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-600 transition-colors focus:outline-none">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.13-3.582m0 0L4.99 8.05m1.347-1.347L17 17m-5-5l3 3m-3-3l-2.828-2.828m0 0L7.172 7.172M9 11a3 3 0 116 0 3 3 0 01-6 0z"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-lg border-slate-200 text-amber-600 focus:ring-amber-500/20 transition-all cursor-pointer" name="remember">
            <label for="remember_me" class="ml-3 text-sm font-bold text-slate-500 cursor-pointer select-none">Se souvenir de moi</label>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-premium-primary w-full justify-center shadow-indigo-100">
                Se connecter
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>

        <div class="mt-8 pt-8 border-t border-slate-100 text-center">
            <p class="text-sm text-slate-500 font-medium">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" class="text-amber-600 font-bold hover:text-amber-700 transition">Créer un compte</a>
            </p>
        </div>
    </form>
</x-guest-layout>
