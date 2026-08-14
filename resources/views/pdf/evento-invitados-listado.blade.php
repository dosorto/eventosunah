<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listado de invitados</title>
    <style>
        @page {
            size: letter portrait;
            margin: 24px 26px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .header {
            margin-bottom: 16px;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 14px 16px;
        }

        .eyebrow {
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.28em;
            text-transform: uppercase;
        }

        h1 {
            margin: 8px 0 6px;
            font-size: 20px;
            line-height: 1.1;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .meta td {
            width: 33.33%;
            padding: 6px 10px 0 0;
            vertical-align: top;
        }

        .meta-label {
            display: block;
            color: #64748b;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .meta-value {
            font-size: 12px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 8px 9px;
            vertical-align: middle;
        }

        th {
            background: #eff6ff;
            color: #334155;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            text-align: left;
        }

        td {
            font-size: 10px;
            height: 38px;
        }

        .col-numero {
            width: 6%;
            text-align: center;
        }

        .col-invitado {
            width: 20%;
        }

        .col-correo {
            width: 22%;
        }

        .col-cupos {
            width: 8%;
            text-align: center;
        }

        .col-dni {
            width: 14%;
        }

        .col-telefono {
            width: 12%;
        }

        .col-firma {
            width: 18%;
        }

        .blank {
            color: transparent;
        }

        .footer {
            margin-top: 10px;
            color: #64748b;
            font-size: 9px;
            text-align: right;
        }
    </style>
</head>
<body>
@php
    $chunks = $invitations->chunk(15);
    $dateLabel = !empty($selectedDate)
        ? \Carbon\Carbon::parse($selectedDate)->format('d/m/Y')
        : trim(($evento->fechainicio?->format('d/m/Y') ?? '') . ' - ' . ($evento->fechafinal?->format('d/m/Y') ?? ''));
@endphp

@foreach ($chunks as $pageIndex => $chunk)
    <section class="page">
        <div class="header">
            <div class="eyebrow">Listado de invitados</div>
            <h1>{{ $evento->nombreevento }}</h1>
            <table class="meta">
                <tr>
                    <td>
                        <span class="meta-label">Fecha</span>
                        <span class="meta-value">{{ $dateLabel }}</span>
                    </td>
                    <td>
                        <span class="meta-label">Lugar</span>
                        <span class="meta-value">{{ $evento->localidad_display }}</span>
                    </td>
                    <td>
                        <span class="meta-label">Página</span>
                        <span class="meta-value">{{ $pageIndex + 1 }} / {{ max(1, $chunks->count()) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="col-numero">N.º</th>
                    <th class="col-invitado">Invitado</th>
                    <th class="col-correo">Correo</th>
                    <th class="col-cupos">Cupos</th>
                    <th class="col-dni">DNI</th>
                    <th class="col-telefono">Teléfono</th>
                    <th class="col-firma">Firma</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chunk as $invitation)
                    <tr>
                        <td class="col-numero">{{ ($pageIndex * 15) + $loop->iteration }}</td>
                        <td>{{ $invitation->nombre_invitado }}</td>
                        <td>{{ $invitation->correo_invitado }}</td>
                        <td class="col-cupos">{{ $invitation->cupos }}</td>
                        <td class="blank">.</td>
                        <td class="blank">.</td>
                        <td class="blank">.</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">Documento generado el {{ now()->format('d/m/Y H:i') }}</div>
    </section>
@endforeach
</body>
</html>
