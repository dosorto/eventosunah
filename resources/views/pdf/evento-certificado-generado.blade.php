<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0;
            size: {{ $pageWidthPt }}pt {{ $pageHeightPt }}pt;
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            font-family: DejaVu Sans, sans-serif;
        }
        .page {
            position: relative;
            width: {{ $pageWidthPt }}pt;
            height: {{ $pageHeightPt }}pt;
            overflow: hidden;
        }
        .page img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: fill;
        }
    </style>
</head>
<body>
    <div class="page">
        <img src="{{ $imageDataUri }}" alt="{{ $title }}">
    </div>
</body>
</html>
