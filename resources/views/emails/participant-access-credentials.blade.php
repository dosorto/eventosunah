@php
    $loginUrl = route('login');
@endphp

@component('mail::message')
# Bienvenido a EventIS

Hola {{ $participantName }},

Se creó tu acceso al sistema a partir de tu inscripción en el evento **{{ $eventName }}**.

**Usuario / correo:** {{ $email }}

**Contraseña temporal:** {{ $password }}

@component('mail::button', ['url' => $loginUrl])
Ingresar a EventIS
@endcomponent

Te recomendamos iniciar sesión y cambiar tu contraseña después de entrar al sistema.

Si no reconoces este registro, puedes ignorar este correo.

Saludos,  
**EventIS**
@endcomponent
