<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de asistencia</title>
    <style>
        @page { margin: 32px 36px; }
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; }
        .header { margin-bottom: 18px; }
        .eyebrow { font-size: 10px; letter-spacing: 0.35em; text-transform: uppercase; color: #475569; }
        h1 { margin: 8px 0 4px; font-size: 22px; }
        .meta { color: #475569; font-size: 11px; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px 12px; vertical-align: middle; }
        thead th { background: #eff6ff; color: #334155; font-size: 10px; letter-spacing: 0.28em; text-transform: uppercase; text-align: left; }
        tbody td { font-size: 11px; }
        .num { width: 52px; }
        .footer { margin-top: 14px; text-align: right; color: #64748b; font-size: 10px; }
    </style>
</head>
<body>
    @php
        $speakerName = trim(($conference->speakerPersona?->nombre ?? '') . ' ' . ($conference->speakerPersona?->apellido ?? ''))
            ?: ($conference->conferencista_nombre_invitado ?: 'Conferencista por definir');
        $speakerProfile = $conference->speakerPersona?->tipoPerfil?->tipoperfil;
    @endphp

    <div class="header">
        <div class="eyebrow">Asistencia por conferencia</div>
        <h1>{{ $conference->nombre }}</h1>
        <div class="meta">
            <div><strong>Evento:</strong> {{ $evento->nombreevento }}</div>
            <div><strong>Conferencista:</strong> {{ $speakerName }}@if($speakerProfile) · {{ $speakerProfile }}@endif</div>
            <div><strong>Fecha del evento:</strong> {{ $evento->fechainicio?->format('d/m/Y') }} - {{ $evento->fechafinal?->format('d/m/Y') }}</div>
            <div><strong>Fecha de la conferencia:</strong> {{ $conference->fecha ? \Illuminate\Support\Carbon::parse($conference->fecha)->format('d/m/Y') : 'Sin fecha' }}</div>
            <div><strong>Horario:</strong> {{ $conference->horaInicio ? \Illuminate\Support\Carbon::parse($conference->horaInicio)->format('h:i a') : '--:--' }} - {{ $conference->horaFin ? \Illuminate\Support\Carbon::parse($conference->horaFin)->format('h:i a') : '--:--' }}</div>
            <div><strong>Lugar:</strong> {{ $conference->lugar ?: 'Lugar por definir' }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="num">Nº.</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendanceRecords as $index => $attendance)
                @php
                    $person = $attendance->eventoRegistro?->persona;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ trim(($person?->nombre ?? '') . ' ' . ($person?->apellido ?? '')) ?: 'Participante' }}</td>
                    <td>{{ $person?->dni ?: '' }}</td>
                    <td>{{ $person?->telefono ?: '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
