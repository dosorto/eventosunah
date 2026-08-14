<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $document['document_title'] }}</title>
    <style>
        @page { margin: 24px; }
        body { margin: 0; font-family: DejaVu Sans, sans-serif; background: #ffffff; }
    </style>
</head>
<body>
    @include('pdf.payments.refund-receipt-content', ['document' => $document])
</body>
</html>
