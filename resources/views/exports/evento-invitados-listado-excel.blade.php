<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listado de invitados</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th colspan="8">Listado de invitados - {{ $evento->nombreevento }}</th>
            </tr>
            <tr>
                <th>N.º</th>
                <th>Invitado</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Código</th>
                <th>Cupos</th>
                <th>Cupos utilizados</th>
                <th>Estado de envío</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invitations as $invitation)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $invitation->nombre_invitado }}</td>
                    <td>{{ $invitation->correo_invitado }}</td>
                    <td>{{ $invitation->telefono_invitado }}</td>
                    <td>{{ $invitation->codigo }}</td>
                    <td>{{ $invitation->cupos }}</td>
                    <td>{{ $invitation->cupos_utilizados }}</td>
                    <td>{{ $invitation->enviada_at ? 'Enviada' : 'Pendiente' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
