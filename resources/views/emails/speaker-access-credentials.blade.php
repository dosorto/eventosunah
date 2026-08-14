@php
    $loginUrl = route('login');
@endphp

@component('mail::message')
# Acceso de conferencista

Hola {{ $speakerName }},

Se creó tu usuario de acceso como conferencista en EventIS.

**Evento:** {{ $eventName }}

**Conferencia:** {{ $conferenceName }}

**Correo:** {{ $email }}

**Contraseña temporal:** {{ $password }}

@component('mail::button', ['url' => $loginUrl])
Iniciar sesión
@endcomponent

Por seguridad, cambia tu contraseña después de iniciar sesión.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
