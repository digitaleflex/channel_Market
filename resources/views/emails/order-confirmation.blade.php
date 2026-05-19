<x-mail::message>
# Merci pour votre achat !

Bonjour {{ $order->client_name ?? 'Client(e)' }},

Nous vous confirmons que votre paiement a été validé avec succès. Merci pour votre confiance !

**Produit :** {{ $order->product->title }}
**Montant payé :** {{ number_format($order->amount, $order->product->currency === 'XOF' ? 0 : 2, ',', ' ') }} {{ $order->product->currency === 'XOF' ? 'CFA' : $order->product->currency }}
**Numéro de commande :** #{{ $order->id }}

@if($order->user_id)
Vous pouvez accéder à votre produit directement depuis votre tableau de bord.
<x-mail::button :url="url('/dashboard')">
Accéder à mon espace
</x-mail::button>
@else
Vous pouvez télécharger votre produit en utilisant le bouton ci-dessous. Veuillez conserver cet e-mail, il contient votre lien d'accès unique.
<x-mail::button :url="route('payment.success', $order)">
Télécharger mon produit
</x-mail::button>
@endif

Si vous avez la moindre question, n'hésitez pas à nous contacter.

Cordialement,
L'équipe {{ config('app.name') }}
</x-mail::message>
