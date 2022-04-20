@component('mail::message')
# Peakify

{{ $date }}

@component('mail::panel')
    Nom d'utilisateur : {{ $username }}
@endcomponent

@component('mail::panel')
    Mot de passe : {{ $password }}
@endcomponent

Compte créé.

@component('mail::button', ['url' => env('app_url')])
click
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
