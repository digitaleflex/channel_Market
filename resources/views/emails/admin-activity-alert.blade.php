<x-mail::message>
# 🔔 Notification d'Activité Admin

Bonjour,

Une action importante a été enregistrée sur la plateforme **{{ config('app.name') }}**.

**Description de l'action :**
### {{ $log->description }}

---

### 📋 Détails de l'activité :
*   **Événement :** `{{ $log->action }}`
*   **Acteur :** {{ $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'Système Automatique' }}
*   **Adresse IP :** `{{ $log->ip_address }}`
*   **Date :** {{ $log->created_at->format('d/m/Y à H:i:s') }}

@if(!empty($log->old_values) || !empty($log->new_values))
### 🔄 Évolution des données :
@if(!empty($log->old_values))
**Anciennes valeurs :**
@foreach($log->old_values as $key => $val)
*   **{{ $key }} :** {{ is_array($val) ? json_encode($val) : $val }}
@endforeach
@endif

@if(!empty($log->new_values))
**Nouvelles valeurs :**
@foreach($log->new_values as $key => $val)
*   **{{ $key }} :** {{ is_array($val) ? json_encode($val) : $val }}
@endforeach
@endif
@endif

<x-mail::button :url="url('/admin/products')">
Accéder à l'administration
</x-mail::button>

Cordialement,<br>
{{ config('app.name') }} Security Monitor
</x-mail::message>
