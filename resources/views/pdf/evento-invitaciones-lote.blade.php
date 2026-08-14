<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Invitaciones</title>
    <style>
        @page {
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            color: #0f172a;
            font-family: DejaVu Sans, sans-serif;
        }

        .page {
            position: relative;
            width: {{ $pageWidthPt }}pt;
            height: {{ $pageHeightPt }}pt;
            page-break-after: always;
            overflow: hidden;
            background: #eef4ff;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .page img {
            width: {{ $pageWidthPt }}pt;
            height: {{ $pageHeightPt }}pt;
            display: block;
        }
    </style>
</head>
<body>
@foreach ($invitations as $invitation)
    <section class="page">
        <img src="{{ $invitation['image_data_uri'] }}" alt="Invitación de {{ $invitation['nombre_invitado'] }}">
    </section>
@endforeach
</body>
</html>
