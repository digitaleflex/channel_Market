<x-mail::message>
# Alerte Système Détectée

Un problème a été identifié sur l'application **{{ config('app.name') }}**.

**Détails de l'erreur :**
{{ $errorMessage }}

@if(!empty($systemContext))
**Contexte additionnel :**
@foreach($systemContext as $key => $value)
- **{{ $key }}** : {{ $value }}
@endforeach
@endif

<x-mail::button :url="config('app.url')">
Accéder au site
</x-mail::button>

Merci,<br>
{{ config('app.name') }} Automated Monitoring
</x-mail::message>
