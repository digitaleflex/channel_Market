@props([
    'title'       => null,
    'description' => 'Découvrez nos produits digitaux exclusifs sur Channel Market. PDF, vidéos, musiques, formations — qualité premium et téléchargement immédiat.',
    'image'       => asset('img/logo.jpg'),
    'url'         => url()->current(),
    'type'        => 'website',
    'keywords'    => 'produits digitaux, marketplace, téléchargement, PDF, formations, Channel Market',
    'robots'      => 'index, follow',
])

@php
    $siteName = config('app.name', 'Channel Market');
    $fullTitle = $title ? $title . ' — ' . $siteName : $siteName . ' | Marketplace de Produits Numériques';
    $siteUrl   = config('app.url', 'https://channelmarket.net');
@endphp

{{-- Primary Meta Tags --}}
<title>{{ $fullTitle }}</title>
<meta name="title"       content="{{ $fullTitle }}">
<meta name="description" content="{{ $description }}">
<meta name="keywords"    content="{{ $keywords }}">
<meta name="robots"      content="{{ $robots }}">
<meta name="author"      content="{{ $siteName }}">
<meta name="language"    content="fr">

{{-- Open Graph / Facebook --}}
<meta property="og:type"        content="{{ $type }}">
<meta property="og:site_name"   content="{{ $siteName }}">
<meta property="og:locale"      content="fr_FR">
<meta property="og:url"         content="{{ $url }}">
<meta property="og:title"       content="{{ $fullTitle }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image"       content="{{ $image }}">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt"    content="{{ $fullTitle }}">

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:site"        content="@@channelmarket">
<meta name="twitter:url"         content="{{ $url }}">
<meta name="twitter:title"       content="{{ $fullTitle }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image"       content="{{ $image }}">

{{-- Canonical --}}
<link rel="canonical" href="{{ $url }}">

{{-- Favicon --}}
<link rel="icon"             type="image/jpeg"   href="{{ asset('img/logo.jpg') }}">
<link rel="apple-touch-icon" sizes="180x180"     href="{{ asset('img/logo.jpg') }}">

{{-- JSON-LD Structured Data --}}
@php
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    'name'     => $siteName,
    'url'      => $siteUrl,
    'description' => $description,
    'inLanguage'  => 'fr-FR',
    'potentialAction' => [
        '@type'       => 'SearchAction',
        'target'      => [
            '@type'       => 'EntryPoint',
            'urlTemplate' => $siteUrl . '/?q={search_term_string}',
        ],
        'query-input' => 'required name=search_term_string',
    ],
];
@endphp
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
