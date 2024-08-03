<?php

namespace App\Services;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\GDLibRenderer;

class QRCodeService
{
    public static function generateWithImagick(string $text, int $size = 300): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new ImagickImageBackEnd()
        );

        $writer = new Writer($renderer);
        $filePath = storage_path('app/public/qrcode.png');
        $writer->writeFile($text, $filePath);

        return $filePath;
    }

    public static function generateWithGDLib(string $text, int $size = 300): string
    {
        $renderer = new GDLibRenderer($size);
        $writer = new Writer($renderer);
        $filePath = storage_path('app/public/qrcode.png');
        $writer->writeFile($text, $filePath);

        return $filePath;
    }

    public static function generateTextQRCode(string $text, int $size = 300): string
    {
        $renderer = new GDLibRenderer($size);
        $writer = new Writer($renderer);

        // Crear un archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), 'qrcode');

        // Escribir el código QR en el archivo temporal
        $writer->writeFile($text, $tempFile);

        // Leer el contenido del archivo temporal
        $qrCodeString = file_get_contents($tempFile);

        // Eliminar el archivo temporal
        unlink($tempFile);

        // Convertir el contenido del archivo en una cadena base64
        return base64_encode($qrCodeString);
    }
}
