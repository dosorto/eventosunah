<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Certificado de participación</title>
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; color: #0f172a; font-family: DejaVu Sans, sans-serif; }
        .page {
            position: relative;
            width: {{ $pageWidthPt }}pt;
            height: {{ $pageHeightPt }}pt;
            overflow: hidden;
            background: #ffffff;
        }
        .page img {
            width: {{ $pageWidthPt }}pt;
            height: {{ $pageHeightPt }}pt;
            display: block;
        }
    </style>
</head>
<body>
    <section class="page">
        <img src="{{ $imageDataUri }}" alt="Certificado de {{ $participantName }}">
    </section>
</body>
</html>
