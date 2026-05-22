<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Rejoignez-nous</h2>
        <p class="text-slate-600 font-medium">Créez votre compte pour commencer vos achats.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Nom complet</label>
            <input id="name" 
                   class="input-premium @error('name') border-red-500 @enderror" 
                   type="text" 
                   name="name" 
                   :value="old('name')" 
                   placeholder="John Doe"
                   required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email</label>
            <input id="email" 
                   class="input-premium @error('email') border-red-500 @enderror" 
                   type="email" 
                   name="email" 
                   :value="old('email')" 
                   placeholder="votre@email.com"
                   required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Mot de passe</label>
            <div class="relative">
                <input id="password" 
                       class="input-premium @error('password') border-red-500 @enderror !pr-12"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       placeholder="••••••••"
                       required autocomplete="new-password" />
                <button type="button" 
                        @click="show = !show" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-amber-600 transition-colors focus:outline-none">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.13-3.582m0 0L4.99 8.05m1.347-1.347L17 17m-5-5l3 3m-3-3l-2.828-2.828m0 0L7.172 7.172M9 11a3 3 0 116 0 3 3 0 01-6 0z"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Confirmer le mot de passe</label>
            <div class="relative">
                <input id="password_confirmation" 
                       class="input-premium !pr-12"
                       :type="show ? 'text' : 'password'"
                       name="password_confirmation" 
                       placeholder="••••••••"
                       required autocomplete="new-password" />
                <button type="button" 
                        @click="show = !show" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-amber-600 transition-colors focus:outline-none">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.13-3.582m0 0L4.99 8.05m1.347-1.347L17 17m-5-5l3 3m-3-3l-2.828-2.828m0 0L7.172 7.172M9 11a3 3 0 116 0 3 3 0 01-6 0z"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-premium-primary w-full justify-center shadow-amber-100">
                Créer mon compte
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </button>
        </div>

        <div class="mt-8 pt-8 border-t border-slate-100 text-center">
            <p class="text-sm text-slate-600 font-medium">
                Déjà inscrit ? 
                <a href="{{ route('login') }}" class="text-amber-600 font-bold hover:text-amber-700 transition">Se connecter</a>
            </p>
        </div>
    </form>
</x-guest-layout>
