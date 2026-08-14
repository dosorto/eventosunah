<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $document['document_title'] }}</title>
    <style>
        @page { margin: 24px; }
        body { margin: 0; }
    </style>
</head>
<body>
    @include('pdf.payments.receipt-content', ['document' => $document])
</body>
</html>
