<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listado de staff</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th colspan="9">Listado de staff - {{ $evento->nombreevento }}</th>
            </tr>
            <tr>
                <th>N.º</th>
                <th>Miembro</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>DNI</th>
                <th>Perfil</th>
                <th>Usuario</th>
                <th>Estado de perfil</th>
                <th>Código staff</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($staffMembers as $member)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $member->nombre }}</td>
                    <td>{{ $member->correo }}</td>
                    <td>{{ $member->telefono }}</td>
                    <td>{{ $member->persona?->dni }}</td>
                    <td>{{ $member->persona?->tipoPerfil?->tipoperfil ?? 'Staff' }}</td>
                    <td>{{ $member->persona?->user?->email ?? 'Sin usuario' }}</td>
                    <td>{{ $member->perfil_completado_at ? 'Completo' : 'Pendiente' }}</td>
                    <td>{{ $member->staff_access_token ? 'STAFF-' . strtoupper(substr((string) $member->staff_access_token, 0, 8)) : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
