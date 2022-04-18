@component('mail::message')
# Peakify

{{ $date }}

Le mot de passe a été changé avec succès.

@component('mail::button', ['url' => env('app_url')])
Click
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
