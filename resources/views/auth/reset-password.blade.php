<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Réinitialisation</h2>
        <p class="text-slate-600 font-medium leading-relaxed">
            Choisissez votre nouveau mot de passe sécurisé pour votre compte Chanel Market.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email</label>
            <input id="email" 
                   class="input-premium @error('email') border-red-500 @enderror" 
                   type="email" 
                   name="email" 
                   :value="old('email', $request->email)" 
                   required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Nouveau mot de passe</label>
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
                Réinitialiser le mot de passe
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </button>
        </div>
    </form>
</x-guest-layout>
