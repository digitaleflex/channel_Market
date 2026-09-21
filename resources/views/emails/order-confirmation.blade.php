<x-mail::message>
# Votre livre numérique est prêt !

Bonjour {{ $order->client_name ?? 'Lecteur / Lectrice' }},

Nous vous confirmons que votre commande sur **{{ config('app.name') }}** a été validée avec succès. Bonne lecture !

**Livre :** {{ $order->product->title }}
@if($order->product->author)
**Auteur :** {{ $order->product->author }}
@endif
**Format :** {{ $order->product->format ?? 'PDF' }}
**Montant réglé :** {{ number_format($order->amount, $order->product->currency === 'XOF' ? 0 : 2, ',', ' ') }} {{ $order->product->currency === 'XOF' ? 'CFA' : $order->product->currency }}
**Numéro de commande :** #{{ $order->id }}

@if($order->user_id)
Vous pouvez retrouver votre livre à tout moment dans votre bibliothèque numérique personnelle.
<x-mail::button :url="url('/dashboard')">
Accéder à Ma Bibliothèque
</x-mail::button>
@else
Vous pouvez télécharger votre livre numérique immédiatement via le bouton sécurisé ci-dessous. Veuillez conserver cet e-mail pour vos futurs téléchargements.
<x-mail::button :url="route('payment.success', $order)">
Télécharger mon livre ({{ $order->product->format ?? 'PDF' }})
</x-mail::button>
@endif

Pour toute question ou assistance de lecture, répondez simplement à cet e-mail.

Excellente lecture,  
L'équipe **{{ config('app.name') }}**
</x-mail::message>
