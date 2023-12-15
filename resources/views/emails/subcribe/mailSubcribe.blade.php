<x-mail::message>
# Recibirás novedade sobre noticias, testimonios,eventos y mucho más.

Gracias por subcribirte a nuestro boletín, obtendrás las últimas novedades.

<x-mail::button :url="route('subscribe.show', $user->token_hash)">
Por favor verifica tu correo electrónico.
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
