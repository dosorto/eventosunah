<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gafetes de participantes</title>
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; color: #0f172a; font-family: DejaVu Sans, sans-serif; }
        .page {
            position: relative;
            width: {{ $pageWidthPt }}pt;
            height: {{ $pageHeightPt }}pt;
            page-break-after: always;
            overflow: hidden;
            background: #ffffff;
        }
        .page:last-child { page-break-after: auto; }
        .page img {
            width: {{ $pageWidthPt }}pt;
            height: {{ $pageHeightPt }}pt;
            display: block;
        }
    </style>
</head>
<body>
@foreach ($participantBadges as $badge)
    <section class="page">
        <img src="{{ $badge['image_data_uri'] }}" alt="Gafete de {{ $badge['nombre'] }}">
    </section>
@endforeach
</body>
</html>
