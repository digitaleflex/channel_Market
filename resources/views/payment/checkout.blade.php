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
                    <input id="country_search" type="search" autocomplete="off"
                           placeholder="Rechercher un pays, ex. France, Nigeria, Canada"
                           class="w-full rounded-xl border-gray-200 focus:border-amber-500 focus:ring-amber-500 mb-3"
                           aria-label="Recherche de pays" />
                    <select id="country_code_selector" class="w-full rounded-xl border-gray-200 bg-white focus:border-amber-500 focus:ring-amber-500" aria-label="Pays">
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
                    const countries = [
                        { iso: 'AF', dial_code: '93', name: 'Afghanistan', flag: '🇦🇫' },
                        { iso: 'AL', dial_code: '355', name: 'Albanie', flag: '🇦🇱' },
                        { iso: 'DZ', dial_code: '213', name: 'Algérie', flag: '🇩🇿' },
                        { iso: 'AD', dial_code: '376', name: 'Andorre', flag: '🇦🇩' },
                        { iso: 'AO', dial_code: '244', name: 'Angola', flag: '🇦🇴' },
                        { iso: 'AI', dial_code: '1', name: 'Anguilla', flag: '🇦🇮' },
                        { iso: 'AR', dial_code: '54', name: 'Argentine', flag: '🇦🇷' },
                        { iso: 'AM', dial_code: '374', name: 'Arménie', flag: '🇦🇲' },
                        { iso: 'AU', dial_code: '61', name: 'Australie', flag: '🇦🇺' },
                        { iso: 'AT', dial_code: '43', name: 'Autriche', flag: '🇦🇹' },
                        { iso: 'AZ', dial_code: '994', name: 'Azerbaïdjan', flag: '🇦🇿' },
                        { iso: 'BH', dial_code: '973', name: 'Bahreïn', flag: '🇧🇭' },
                        { iso: 'BD', dial_code: '880', name: 'Bangladesh', flag: '🇧🇩' },
                        { iso: 'BY', dial_code: '375', name: 'Biélorussie', flag: '🇧🇾' },
                        { iso: 'BE', dial_code: '32', name: 'Belgique', flag: '🇧🇪' },
                        { iso: 'BZ', dial_code: '501', name: 'Belize', flag: '🇧🇿' },
                        { iso: 'BJ', dial_code: '229', name: 'Bénin', flag: '🇧🇯' },
                        { iso: 'BT', dial_code: '975', name: 'Bhoutan', flag: '🇧🇹' },
                        { iso: 'BO', dial_code: '591', name: 'Bolivie', flag: '🇧🇴' },
                        { iso: 'BA', dial_code: '387', name: 'Bosnie-Herzégovine', flag: '🇧🇦' },
                        { iso: 'BW', dial_code: '267', name: 'Botswana', flag: '🇧🇼' },
                        { iso: 'BR', dial_code: '55', name: 'Brésil', flag: '🇧🇷' },
                        { iso: 'BN', dial_code: '673', name: 'Brunei', flag: '🇧🇳' },
                        { iso: 'BG', dial_code: '359', name: 'Bulgarie', flag: '🇧🇬' },
                        { iso: 'BF', dial_code: '226', name: 'Burkina Faso', flag: '🇧🇫' },
                        { iso: 'BI', dial_code: '257', name: 'Burundi', flag: '🇧🇮' },
                        { iso: 'KH', dial_code: '855', name: 'Cambodge', flag: '🇰🇭' },
                        { iso: 'CM', dial_code: '237', name: 'Cameroun', flag: '🇨🇲' },
                        { iso: 'CA', dial_code: '1', name: 'Canada', flag: '🇨🇦' },
                        { iso: 'CV', dial_code: '238', name: 'Cap-Vert', flag: '🇨🇻' },
                        { iso: 'CF', dial_code: '236', name: 'Centrafrique', flag: '🇨🇫' },
                        { iso: 'TD', dial_code: '235', name: 'Tchad', flag: '🇹🇩' },
                        { iso: 'CL', dial_code: '56', name: 'Chili', flag: '🇨🇱' },
                        { iso: 'CN', dial_code: '86', name: 'Chine', flag: '🇨🇳' },
                        { iso: 'CY', dial_code: '357', name: 'Chypre', flag: '🇨🇾' },
                        { iso: 'CO', dial_code: '57', name: 'Colombie', flag: '🇨🇴' },
                        { iso: 'KM', dial_code: '269', name: 'Comores', flag: '🇰🇲' },
                        { iso: 'CG', dial_code: '242', name: 'Congo', flag: '🇨🇬' },
                        { iso: 'CD', dial_code: '243', name: 'République démocratique du Congo', flag: '🇨🇩' },
                        { iso: 'CR', dial_code: '506', name: 'Costa Rica', flag: '🇨🇷' },
                        { iso: 'CI', dial_code: '225', name: 'Côte d’Ivoire', flag: '🇨🇮' },
                        { iso: 'HR', dial_code: '385', name: 'Croatie', flag: '🇭🇷' },
                        { iso: 'CU', dial_code: '53', name: 'Cuba', flag: '🇨🇺' },
                        { iso: 'DK', dial_code: '45', name: 'Danemark', flag: '🇩🇰' },
                        { iso: 'DM', dial_code: '1', name: 'Dominique', flag: '🇩🇲' },
                        { iso: 'EC', dial_code: '593', name: 'Équateur', flag: '🇪🇨' },
                        { iso: 'EG', dial_code: '20', name: 'Égypte', flag: '🇪🇬' },
                        { iso: 'SV', dial_code: '503', name: 'Salvador', flag: '🇸🇻' },
                        { iso: 'EE', dial_code: '372', name: 'Estonie', flag: '🇪🇪' },
                        { iso: 'ET', dial_code: '251', name: 'Éthiopie', flag: '🇪🇹' },
                        { iso: 'FJ', dial_code: '679', name: 'Fidji', flag: '🇫🇯' },
                        { iso: 'FI', dial_code: '358', name: 'Finlande', flag: '🇫🇮' },
                        { iso: 'FR', dial_code: '33', name: 'France', flag: '🇫🇷' },
                        { iso: 'GA', dial_code: '241', name: 'Gabon', flag: '🇬🇦' },
                        { iso: 'GM', dial_code: '220', name: 'Gambie', flag: '🇬🇲' },
                        { iso: 'GE', dial_code: '995', name: 'Géorgie', flag: '🇬🇪' },
                        { iso: 'DE', dial_code: '49', name: 'Allemagne', flag: '🇩🇪' },
                        { iso: 'GH', dial_code: '233', name: 'Ghana', flag: '🇬🇭' },
                        { iso: 'GR', dial_code: '30', name: 'Grèce', flag: '🇬🇷' },
                        { iso: 'GD', dial_code: '1', name: 'Grenade', flag: '🇬🇩' },
                        { iso: 'GU', dial_code: '1', name: 'Guam', flag: '🇬🇺' },
                        { iso: 'GT', dial_code: '502', name: 'Guatemala', flag: '🇬🇹' },
                        { iso: 'GN', dial_code: '224', name: 'Guinée', flag: '🇬🇳' },
                        { iso: 'GW', dial_code: '245', name: 'Guinée-Bissau', flag: '🇬🇼' },
                        { iso: 'GY', dial_code: '592', name: 'Guyana', flag: '🇬🇾' },
                        { iso: 'HT', dial_code: '509', name: 'Haïti', flag: '🇭🇹' },
                        { iso: 'HN', dial_code: '504', name: 'Honduras', flag: '🇭🇳' },
                        { iso: 'HK', dial_code: '852', name: 'Hong Kong', flag: '🇭🇰' },
                        { iso: 'HU', dial_code: '36', name: 'Hongrie', flag: '🇭🇺' },
                        { iso: 'IS', dial_code: '354', name: 'Islande', flag: '🇮🇸' },
                        { iso: 'IN', dial_code: '91', name: 'Inde', flag: '🇮🇳' },
                        { iso: 'ID', dial_code: '62', name: 'Indonésie', flag: '🇮🇩' },
                        { iso: 'IR', dial_code: '98', name: 'Iran', flag: '🇮🇷' },
                        { iso: 'IQ', dial_code: '964', name: 'Irak', flag: '🇮🇶' },
                        { iso: 'IE', dial_code: '353', name: 'Irlande', flag: '🇮🇪' },
                        { iso: 'IL', dial_code: '972', name: 'Israël', flag: '🇮🇱' },
                        { iso: 'IT', dial_code: '39', name: 'Italie', flag: '🇮🇹' },
                        { iso: 'JM', dial_code: '1', name: 'Jamaïque', flag: '🇯🇲' },
                        { iso: 'JP', dial_code: '81', name: 'Japon', flag: '🇯🇵' },
                        { iso: 'JO', dial_code: '962', name: 'Jordanie', flag: '🇯🇴' },
                        { iso: 'KZ', dial_code: '7', name: 'Kazakhstan', flag: '🇰🇿' },
                        { iso: 'KE', dial_code: '254', name: 'Kenya', flag: '🇰🇪' },
                        { iso: 'KI', dial_code: '686', name: 'Kiribati', flag: '🇰🇮' },
                        { iso: 'KW', dial_code: '965', name: 'Koweït', flag: '🇰🇼' },
                        { iso: 'KG', dial_code: '996', name: 'Kirghizistan', flag: '🇰🇬' },
                        { iso: 'LA', dial_code: '856', name: 'Laos', flag: '🇱🇦' },
                        { iso: 'LV', dial_code: '371', name: 'Lettonie', flag: '🇱🇻' },
                        { iso: 'LB', dial_code: '961', name: 'Liban', flag: '🇱🇧' },
                        { iso: 'LR', dial_code: '231', name: 'Libéria', flag: '🇱🇷' },
                        { iso: 'LY', dial_code: '218', name: 'Libye', flag: '🇱🇾' },
                        { iso: 'LI', dial_code: '423', name: 'Liechtenstein', flag: '🇱🇮' },
                        { iso: 'LT', dial_code: '370', name: 'Lituanie', flag: '🇱🇹' },
                        { iso: 'LU', dial_code: '352', name: 'Luxembourg', flag: '🇱🇺' },
                        { iso: 'MO', dial_code: '853', name: 'Macao', flag: '🇲🇴' },
                        { iso: 'MK', dial_code: '389', name: 'Macédoine du Nord', flag: '🇲🇰' },
                        { iso: 'MG', dial_code: '261', name: 'Madagascar', flag: '🇲🇬' },
                        { iso: 'MW', dial_code: '265', name: 'Malawi', flag: '🇲🇼' },
                        { iso: 'MY', dial_code: '60', name: 'Malaisie', flag: '🇲🇾' },
                        { iso: 'MV', dial_code: '960', name: 'Maldives', flag: '🇲🇻' },
                        { iso: 'ML', dial_code: '223', name: 'Mali', flag: '🇲🇱' },
                        { iso: 'MT', dial_code: '356', name: 'Malte', flag: '🇲🇹' },
                        { iso: 'MH', dial_code: '692', name: 'Îles Marshall', flag: '🇲🇭' },
                        { iso: 'MQ', dial_code: '596', name: 'Martinique', flag: '🇲🇶' },
                        { iso: 'MR', dial_code: '222', name: 'Mauritanie', flag: '🇲🇷' },
                        { iso: 'MU', dial_code: '230', name: 'Maurice', flag: '🇲🇺' },
                        { iso: 'YT', dial_code: '262', name: 'Mayotte', flag: '🇾🇹' },
                        { iso: 'MX', dial_code: '52', name: 'Mexique', flag: '🇲🇽' },
                        { iso: 'FM', dial_code: '691', name: 'Micronésie', flag: '🇫🇲' },
                        { iso: 'MD', dial_code: '373', name: 'Moldavie', flag: '🇲🇩' },
                        { iso: 'MC', dial_code: '377', name: 'Monaco', flag: '🇲🇨' },
                        { iso: 'MN', dial_code: '976', name: 'Mongolie', flag: '🇲🇳' },
                        { iso: 'ME', dial_code: '382', name: 'Monténégro', flag: '🇲🇪' },
                        { iso: 'MA', dial_code: '212', name: 'Maroc', flag: '🇲🇦' },
                        { iso: 'MZ', dial_code: '258', name: 'Mozambique', flag: '🇲🇿' },
                        { iso: 'MM', dial_code: '95', name: 'Myanmar', flag: '🇲🇲' },
                        { iso: 'NA', dial_code: '264', name: 'Namibie', flag: '🇳🇦' },
                        { iso: 'NR', dial_code: '674', name: 'Nauru', flag: '🇳🇷' },
                        { iso: 'NP', dial_code: '977', name: 'Népal', flag: '🇳🇵' },
                        { iso: 'NL', dial_code: '31', name: 'Pays-Bas', flag: '🇳🇱' },
                        { iso: 'NZ', dial_code: '64', name: 'Nouvelle-Zélande', flag: '🇳🇿' },
                        { iso: 'NI', dial_code: '505', name: 'Nicaragua', flag: '🇳🇮' },
                        { iso: 'NE', dial_code: '227', name: 'Niger', flag: '🇳🇪' },
                        { iso: 'NG', dial_code: '234', name: 'Nigeria', flag: '🇳🇬' },
                        { iso: 'NO', dial_code: '47', name: 'Norvège', flag: '🇳🇴' },
                        { iso: 'OM', dial_code: '968', name: 'Oman', flag: '🇴🇲' },
                        { iso: 'PK', dial_code: '92', name: 'Pakistan', flag: '🇵🇰' },
                        { iso: 'PW', dial_code: '680', name: 'Palaos', flag: '🇵🇼' },
                        { iso: 'PA', dial_code: '507', name: 'Panama', flag: '🇵🇦' },
                        { iso: 'PG', dial_code: '675', name: 'Papouasie-Nouvelle-Guinée', flag: '🇵🇬' },
                        { iso: 'PY', dial_code: '595', name: 'Paraguay', flag: '🇵🇾' },
                        { iso: 'PE', dial_code: '51', name: 'Pérou', flag: '🇵🇪' },
                        { iso: 'PH', dial_code: '63', name: 'Philippines', flag: '🇵🇭' },
                        { iso: 'PL', dial_code: '48', name: 'Pologne', flag: '🇵🇱' },
                        { iso: 'PT', dial_code: '351', name: 'Portugal', flag: '🇵🇹' },
                        { iso: 'QA', dial_code: '974', name: 'Qatar', flag: '🇶🇦' },
                        { iso: 'RO', dial_code: '40', name: 'Roumanie', flag: '🇷🇴' },
                        { iso: 'RU', dial_code: '7', name: 'Russie', flag: '🇷🇺' },
                        { iso: 'RW', dial_code: '250', name: 'Rwanda', flag: '🇷🇼' },
                        { iso: 'KN', dial_code: '1', name: 'Saint-Kitts-et-Nevis', flag: '🇰🇳' },
                        { iso: 'LC', dial_code: '1', name: 'Sainte-Lucie', flag: '🇱🇨' },
                        { iso: 'VC', dial_code: '1', name: 'Saint-Vincent-et-les-Grenadines', flag: '🇻🇨' },
                        { iso: 'WS', dial_code: '685', name: 'Samoa', flag: '🇼🇸' },
                        { iso: 'SM', dial_code: '378', name: 'Saint-Marin', flag: '🇸🇲' },
                        { iso: 'ST', dial_code: '239', name: 'Sao Tomé-et-Principe', flag: '🇸🇹' },
                        { iso: 'SA', dial_code: '966', name: 'Arabie Saoudite', flag: '🇸🇦' },
                        { iso: 'SN', dial_code: '221', name: 'Sénégal', flag: '🇸🇳' },
                        { iso: 'RS', dial_code: '381', name: 'Serbie', flag: '🇷🇸' },
                        { iso: 'SC', dial_code: '248', name: 'Seychelles', flag: '🇸🇨' },
                        { iso: 'SL', dial_code: '232', name: 'Sierra Leone', flag: '🇸🇱' },
                        { iso: 'SG', dial_code: '65', name: 'Singapour', flag: '🇸🇬' },
                        { iso: 'SK', dial_code: '421', name: 'Slovaquie', flag: '🇸🇰' },
                        { iso: 'SI', dial_code: '386', name: 'Slovénie', flag: '🇸🇮' },
                        { iso: 'SB', dial_code: '677', name: 'Îles Salomon', flag: '🇸🇧' },
                        { iso: 'SO', dial_code: '252', name: 'Somalie', flag: '🇸🇴' },
                        { iso: 'ZA', dial_code: '27', name: 'Afrique du Sud', flag: '🇿🇦' },
                        { iso: 'SS', dial_code: '211', name: 'Soudan du Sud', flag: '🇸🇸' },
                        { iso: 'ES', dial_code: '34', name: 'Espagne', flag: '🇪🇸' },
                        { iso: 'LK', dial_code: '94', name: 'Sri Lanka', flag: '🇱🇰' },
                        { iso: 'SD', dial_code: '249', name: 'Soudan', flag: '🇸🇩' },
                        { iso: 'SE', dial_code: '46', name: 'Suède', flag: '🇸🇪' },
                        { iso: 'CH', dial_code: '41', name: 'Suisse', flag: '🇨🇭' },
                        { iso: 'SY', dial_code: '963', name: 'Syrie', flag: '🇸🇾' },
                        { iso: 'TW', dial_code: '886', name: 'Taïwan', flag: '🇹🇼' },
                        { iso: 'TJ', dial_code: '992', name: 'Tadjikistan', flag: '🇹🇯' },
                        { iso: 'TZ', dial_code: '255', name: 'Tanzanie', flag: '🇹🇿' },
                        { iso: 'TH', dial_code: '66', name: 'Thaïlande', flag: '🇹🇭' },
                        { iso: 'TG', dial_code: '228', name: 'Togo', flag: '🇹🇬' },
                        { iso: 'TO', dial_code: '676', name: 'Tonga', flag: '🇹🇴' },
                        { iso: 'TN', dial_code: '216', name: 'Tunisie', flag: '🇹🇳' },
                        { iso: 'TR', dial_code: '90', name: 'Turquie', flag: '🇹🇷' },
                        { iso: 'TM', dial_code: '993', name: 'Turkménistan', flag: '🇹🇲' },
                        { iso: 'TV', dial_code: '688', name: 'Tuvalu', flag: '🇹🇻' },
                        { iso: 'UG', dial_code: '256', name: 'Ouganda', flag: '🇺🇬' },
                        { iso: 'UA', dial_code: '380', name: 'Ukraine', flag: '🇺🇦' },
                        { iso: 'AE', dial_code: '971', name: 'Émirats arabes unis', flag: '🇦🇪' },
                        { iso: 'GB', dial_code: '44', name: 'Royaume-Uni', flag: '🇬🇧' },
                        { iso: 'US', dial_code: '1', name: 'États-Unis', flag: '🇺🇸' },
                        { iso: 'UY', dial_code: '598', name: 'Uruguay', flag: '🇺🇾' },
                        { iso: 'UZ', dial_code: '998', name: 'Ouzbékistan', flag: '🇺🇿' },
                        { iso: 'VU', dial_code: '678', name: 'Vanuatu', flag: '🇻🇺' },
                        { iso: 'VA', dial_code: '379', name: 'Vatican', flag: '🇻🇦' },
                        { iso: 'VE', dial_code: '58', name: 'Venezuela', flag: '🇻🇪' },
                        { iso: 'VN', dial_code: '84', name: 'Viêt Nam', flag: '🇻🇳' },
                        { iso: 'YE', dial_code: '967', name: 'Yémen', flag: '🇾🇪' },
                        { iso: 'ZM', dial_code: '260', name: 'Zambie', flag: '🇿🇲' },
                        { iso: 'ZW', dial_code: '263', name: 'Zimbabwe', flag: '🇿🇼' }
                    ];

                    const countrySearch = document.getElementById('country_search');
                    const countrySelect = document.getElementById('country_code_selector');
                    const phoneInput = document.getElementById('phone_input');

                    const buildOptions = (list) => list.map(c => `<option value="${c.dial_code}" data-iso="${c.iso}">${c.flag} +${c.dial_code} ${c.name}</option>`).join('');
                    const populateCountryOptions = (filter = '') => {
                        const trimmed = filter.trim().toLowerCase();
                        const filtered = trimmed
                            ? countries.filter(c => c.name.toLowerCase().includes(trimmed) || c.dial_code.includes(trimmed))
                            : countries;
                        countrySelect.innerHTML = filtered.length
                            ? buildOptions(filtered)
                            : '<option disabled>Aucun pays trouvé</option>';
                    };

                    populateCountryOptions();

                    const initialValue = phoneInput.value.trim();
                    const initialDial = (initialValue.match(/^\+([0-9]{1,3})/) || [])[1] || '229';
                    if (countries.some(c => c.dial_code === initialDial)) {
                        countrySelect.value = initialDial;
                    }

                    const normalizePhoneValue = () => {
                        let value = phoneInput.value.replace(/[^0-9+ ]+/g, '');
                        if (value && !value.startsWith('+')) {
                            value = '+' + value;
                        }
                        phoneInput.value = value.replace(/\s+/g, ' ').trim();
                    };

                    countrySearch.addEventListener('input', function () {
                        populateCountryOptions(this.value);
                    });

                    countrySelect.addEventListener('change', function () {
                        const dialCode = countrySelect.value;
                        let value = phoneInput.value.replace(/\s+/g, ' ').trim();
                        const rest = value.startsWith('+') ? value.replace(/^\+[0-9]{1,3}\s*/, '') : value;
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
