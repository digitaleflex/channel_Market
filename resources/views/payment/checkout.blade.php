@extends('layouts.public')
@section('title', 'Paiement Sécurisé')
@section('content')
@push('tracking-events')
    <script>
        if (typeof fbq === 'function') {
            fbq('track', 'InitiateCheckout', {
                content_name: '{{ $product->title }}',
                content_ids: ['{{ $product->id }}'],
                content_type: 'product',
                currency: '{{ $product->currency ?? 'XOF' }}'
            });
        }
        if (typeof gtag === 'function') {
            gtag('event', 'begin_checkout', {
                items: [{
                    item_id: '{{ $product->id }}',
                    item_name: '{{ $product->title }}',
                    price: {{ $product->price }},
                    currency: '{{ $product->currency ?? 'XOF' }}'
                }]
            });
        }
    </script>
@endpush
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-gray-100 text-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full blur-2xl opacity-50"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-gradient-to-tr from-green-100 to-teal-100 rounded-full blur-2xl opacity-50"></div>

        <div class="relative z-10">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2 font-['Outfit']">Finaliser votre achat</h2>
            <p class="text-gray-500 mb-10">Vous êtes sur le point d'acquérir "{{ $product->title }}"</p>

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 mb-10 inline-block text-left w-full max-w-sm">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-600 font-medium">Prix unitaire</span>
                    <span class="text-gray-900 font-bold">{{ number_format($product->price, $product->currency === 'XOF' ? 0 : 2, ',', ' ') }} {{ $product->currency === 'XOF' ? 'CFA' : $product->currency }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-600 font-medium">Frais de traitement</span>
                    <span class="text-green-600 font-bold">0,00 {{ $product->currency === 'XOF' ? 'CFA' : $product->currency }}</span>
                </div>
                <div class="w-full h-px bg-gray-200 mb-4"></div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-900 font-bold text-lg">Total à payer</span>
                    <span class="text-amber-600 font-black text-2xl">{{ number_format($product->price, $product->currency === 'XOF' ? 0 : 2, '', '') }} {{ $product->currency === 'XOF' ? 'CFA' : $product->currency }}</span>
                </div>
            </div>

            @if(session('error'))
                <div class="max-w-xl mx-auto mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm text-left">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.init', $product) }}" class="max-w-xl mx-auto text-left">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Prénom</label>
                        <input name="first_name" value="{{ old('first_name') }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500" />
                        @error('first_name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nom</label>
                        <input name="last_name" value="{{ old('last_name') }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500" />
                        @error('last_name')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500" />
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pays</label>
                    <select id="country_code_selector" class="w-full rounded-xl border-gray-200 bg-white focus:border-amber-500 focus:ring-amber-500" aria-label="Pays">
                        <option value="229" selected>🇧🇯 +229 Bénin</option>
                        <option value="20">🇪🇬 +20 Égypte</option>
                        <option value="211">🇸🇸 +211 Soudan du Sud</option>
                        <option value="212">🇲🇦 +212 Maroc</option>
                        <option value="213">🇩🇿 +213 Algérie</option>
                        <option value="216">🇹🇳 +216 Tunisie</option>
                        <option value="218">🇱🇾 +218 Libye</option>
                        <option value="220">🇬🇲 +220 Gambie</option>
                        <option value="221">🇸🇳 +221 Sénégal</option>
                        <option value="222">🇲🇷 +222 Mauritanie</option>
                        <option value="223">🇲🇱 +223 Mali</option>
                        <option value="224">🇬🇳 +224 Guinée</option>
                        <option value="225">🇨🇮 +225 Côte d'Ivoire</option>
                        <option value="226">🇧🇫 +226 Burkina Faso</option>
                        <option value="227">🇳🇪 +227 Niger</option>
                        <option value="228">🇹🇬 +228 Togo</option>
                        <option value="230">🇲🇺 +230 Maurice</option>
                        <option value="231">🇱🇷 +231 Libéria</option>
                        <option value="232">🇸🇱 +232 Sierra Leone</option>
                        <option value="233">🇬🇭 +233 Ghana</option>
                        <option value="234">🇳🇬 +234 Nigeria</option>
                        <option value="235">🇹🇩 +235 Tchad</option>
                        <option value="236">🇨🇫 +236 Centrafrique</option>
                        <option value="237">🇨🇲 +237 Cameroun</option>
                        <option value="238">🇨🇻 +238 Cap-Vert</option>
                        <option value="239">🇸🇹 +239 São Tomé-et-Principe</option>
                        <option value="240">🇬🇶 +240 Guinée équatoriale</option>
                        <option value="241">🇬🇦 +241 Gabon</option>
                        <option value="242">🇨🇬 +242 Congo</option>
                        <option value="243">🇨🇩 +243 République démocratique du Congo</option>
                        <option value="244">🇦🇴 +244 Angola</option>
                        <option value="245">🇬🇼 +245 Guinée-Bissau</option>
                        <option value="246">🇮🇴 +246 Territoire britannique de l'océan Indien</option>
                        <option value="248">🇸🇨 +248 Seychelles</option>
                        <option value="249">🇸🇩 +249 Soudan</option>
                        <option value="250">🇷🇼 +250 Rwanda</option>
                        <option value="251">🇪🇹 +251 Éthiopie</option>
                        <option value="252">🇸🇴 +252 Somalie</option>
                        <option value="253">🇩🇯 +253 Djibouti</option>
                        <option value="254">🇰🇪 +254 Kenya</option>
                        <option value="255">🇹🇿 +255 Tanzanie</option>
                        <option value="256">🇺🇬 +256 Ouganda</option>
                        <option value="257">🇧🇮 +257 Burundi</option>
                        <option value="258">🇲🇿 +258 Mozambique</option>
                        <option value="260">🇿🇲 +260 Zambie</option>
                        <option value="261">🇲🇬 +261 Madagascar</option>
                        <option value="262">🇷🇪 +262 Réunion</option>
                        <option value="263">🇿🇼 +263 Zimbabwe</option>
                        <option value="264">🇳🇦 +264 Namibie</option>
                        <option value="265">🇲🇼 +265 Malawi</option>
                        <option value="266">🇱🇸 +266 Lesotho</option>
                        <option value="267">🇧🇼 +267 Botswana</option>
                        <option value="268">🇸🇿 +268 Eswatini</option>
                        <option value="269">🇰🇲 +269 Comores</option>
                        <option value="27">🇿🇦 +27 Afrique du Sud</option>
                        <option value="290">🇸🇭 +290 Sainte-Hélène</option>
                        <option value="291">🇪🇷 +291 Érythrée</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Numéro WhatsApp (Obligatoire)</label>
                    <input id="phone_input" type="tel" name="phone" value="{{ old('phone', '+229 ') }}" required placeholder="Ex: +229 01020304"
                           pattern="^\+[0-9 ]{6,25}$"
                           class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500" />
                    <p class="text-xs text-gray-400 mt-2">Format international requis : commencez par <strong>+</strong> puis indicatif pays et numéro local.</p>
                    @error('phone')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="inline-flex items-center justify-center w-full px-6 py-3 bg-amber-600 text-white font-semibold rounded-2xl hover:bg-amber-700 transition shadow-md hover:shadow-lg">
                        Payer
                    </button>
                </div>
            </form>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const countrySelect = document.getElementById('country_code_selector');
                    const phoneInput = document.getElementById('phone_input');

                    const normalizePhoneValue = () => {
                        let value = phoneInput.value.replace(/[^0-9+ ]+/g, '');
                        if (value && !value.startsWith('+')) {
                            value = '+' + value;
                        }
                        phoneInput.value = value.replace(/\s+/g, ' ').trim();
                    };

                    countrySelect.addEventListener('change', function () {
                        const dialCode = countrySelect.value;
                        let value = phoneInput.value.replace(/\s+/g, ' ').trim();
                        if (!value.startsWith('+')) {
                            phoneInput.value = '+' + dialCode + ' ';
                            return;
                        }
                        const rest = value.replace(/^\+[0-9]{1,3}\s*/, '');
                        phoneInput.value = '+' + dialCode + (rest ? ' ' + rest : '');
                    });

                    phoneInput.addEventListener('blur', normalizePhoneValue);
                    phoneInput.addEventListener('input', normalizePhoneValue);
                });
            </script>

            <p class="text-xs text-gray-400 mt-6 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Paiement 100% sécurisé
            </p>
        </div>
    </div>
</div>
@endsection
