<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Mot de passe oublié ?</h2>
        <p class="text-slate-600 font-medium leading-relaxed">
            Pas de soucis. Indiquez-nous votre adresse email et nous vous enverrons un lien de réinitialisation.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                   required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-premium-primary w-full justify-center shadow-amber-100">
                Envoyer le lien de réinitialisation
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </button>
        </div>

        <div class="mt-8 pt-8 border-t border-slate-100 text-center">
            <a href="{{ route('login') }}" class="text-sm text-slate-600 font-bold hover:text-amber-600 transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Retour à la connexion
            </a>
        </div>
    </form>
</x-guest-layout>
