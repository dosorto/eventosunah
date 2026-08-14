<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listado de inscripciones</title>
    <style>
        @page { size: letter portrait; margin: 24px 26px; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #0f172a; font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .page { page-break-after: always; }
        .page:last-child { page-break-after: auto; }
        .header { margin-bottom: 12px; border: 1px solid #cbd5e1; border-radius: 14px; padding: 12px 14px; }
        .eyebrow { color: #64748b; font-size: 10px; font-weight: 700; letter-spacing: 0.28em; text-transform: uppercase; }
        h1 { margin: 6px 0 4px; font-size: 19px; line-height: 1.05; }
        .meta { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .meta td { width: 33.33%; padding: 4px 8px 0 0; vertical-align: top; }
        .meta-label { display: block; color: #64748b; font-size: 9px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 3px; }
        .meta-value { font-size: 12px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; vertical-align: middle; }
        th { background: #eff6ff; color: #334155; font-size: 9px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; text-align: left; }
        td { font-size: 10px; height: 32px; }
        .col-numero { width: 8%; text-align: center; }
        .col-nombre { width: 26%; }
        .col-identidad { width: 18%; }
        .col-telefono { width: 16%; }
        .col-pago { width: 16%; }
        .col-firma { width: 16%; }
        .blank { color: transparent; }
        .footer { margin-top: 10px; color: #64748b; font-size: 9px; text-align: right; }
    </style>
</head>
<body>
@php
    $chunks = $registrationRecords->chunk(15);
    $dateLabel = !empty($selectedDate)
        ? \Carbon\Carbon::parse($selectedDate)->format('d/m/Y')
        : trim(($evento->fechainicio?->format('d/m/Y') ?? '') . ' - ' . ($evento->fechafinal?->format('d/m/Y') ?? ''));
@endphp

@foreach ($chunks as $pageIndex => $chunk)
    <section class="page">
        <div class="header">
            <div class="eyebrow">Listado de inscripciones</div>
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
                    <th class="col-nombre">Nombre completo</th>
                    <th class="col-identidad">Identidad</th>
                    <th class="col-telefono">Teléfono</th>
                    <th class="col-pago">Pago</th>
                    @if (!empty($includeSignature))
                        <th class="col-firma">Firma</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($chunk as $registrationRecord)
                    @php
                        $persona = $registrationRecord->persona;
                        $fullName = trim(($persona?->nombre ?? '') . ' ' . ($persona?->apellido ?? ''));
                        $paymentMethod = $registrationRecord->metodoPago?->nombre;
                        $paymentLabel = (float) $registrationRecord->precio_aplicado > 0
                            ? trim(($paymentMethod ? $paymentMethod . ' · ' : '') . $registrationRecord->formattedPrecioAplicado(true))
                            : 'Gratuita';
                    @endphp
                    <tr>
                        <td class="col-numero">{{ ($pageIndex * 15) + $loop->iteration }}</td>
                        <td>{{ $fullName !== '' ? $fullName : 'Sin nombre' }}</td>
                        <td>{{ $persona?->dni ?: 'Sin identidad' }}</td>
                        <td>{{ $persona?->telefono ?: 'Sin teléfono' }}</td>
                        <td>{{ $paymentLabel }}</td>
                        @if (!empty($includeSignature))
                            <td class="blank">.</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">Documento generado el {{ now()->format('d/m/Y H:i') }}</div>
    </section>
@endforeach
</body>
</html>
