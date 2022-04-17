@component('mail::message')
# {{ config('app.name') }}

Réinitialiser le mot de passe.

@component('mail::button', ['url' => $url,'color' => 'success'])
    Réinitialiser
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
