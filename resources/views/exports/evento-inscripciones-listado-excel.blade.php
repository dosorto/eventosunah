<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listado de inscripciones</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th colspan="10">Listado de inscripciones - {{ $evento->nombreevento }}</th>
            </tr>
            <tr>
                <th>N.º</th>
                <th>Participante</th>
                <th>DNI</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Perfil</th>
                <th>Método de pago</th>
                <th>Precio aplicado</th>
                <th>Estado</th>
                <th>Estado de pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registrationRecords as $registrationRecord)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ trim(($registrationRecord->persona?->nombre ?? '') . ' ' . ($registrationRecord->persona?->apellido ?? '')) ?: 'Sin nombre' }}</td>
                    <td>{{ $registrationRecord->persona?->dni }}</td>
                    <td>{{ $registrationRecord->persona?->correo }}</td>
                    <td>{{ $registrationRecord->persona?->telefono }}</td>
                    <td>{{ $registrationRecord->tipoPerfil?->tipoperfil ?? 'Sin perfil' }}</td>
                    <td>{{ $registrationRecord->metodoPago?->nombre ?? ($registrationRecord->estado_pago === 'no_aplica' ? 'No aplica' : '') }}</td>
                    <td>{{ (float) $registrationRecord->precio_aplicado > 0 ? $registrationRecord->formattedPrecioAplicado(true) : 'Gratuita' }}</td>
                    <td>{{ $registrationRecord->estado }}</td>
                    <td>{{ $registrationRecord->estado_pago }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
