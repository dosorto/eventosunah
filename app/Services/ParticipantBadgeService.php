<?php

namespace App\Services;

use App\Models\Evento;
use App\Models\EventoRegistro;
use Illuminate\Support\Str;

class ParticipantBadgeService
{
    private const PAGE_SIZE_RATIOS = [
        'Carta' => [8.5, 11],
        'Oficio' => [8.5, 13],
        'Legal' => [8.5, 14],
        'A4' => [8.27, 11.69],
        'A3' => [11.69, 16.54],
        'Personalizado' => [8.5, 11],
    ];

    public function isAvailable(EventoRegistro $registration): bool
    {
        return in_array((string) $registration->estado_pago, ['pagado', 'no_aplica'], true)
            && (string) $registration->estado !== 'cancelado';
    }

    public function code(EventoRegistro $registration): string
    {
        return 'REG-' . $registration->evento_id . '-' . $registration->id;
    }

    public function filename(EventoRegistro $registration): string
    {
        $fullName = trim(($registration->persona?->nombre ?? '') . ' ' . ($registration->persona?->apellido ?? '')) ?: 'participante';

        return 'gafete-participante-' . Str::slug($fullName) . '.png';
    }

    public function renderPng(EventoRegistro $registration, ?int $targetCanvasWidth = null): string
    {
        $evento = $registration->evento;

        if (! $evento) {
            throw new \RuntimeException('El registro no tiene evento asociado.');
        }

        $design = $this->design($evento) ?? [];
        [$canvasWidth, $canvasHeight] = $this->canvasDimensions(
            $design['page_size'] ?? 'Carta',
            $design['orientation'] ?? 'Vertical',
            $targetCanvasWidth
        );

        $image = $this->makeCanvas($evento, $canvasWidth, $canvasHeight);

        $fullName = trim((string) (($registration->persona?->nombre ?? '') . ' ' . ($registration->persona?->apellido ?? '')));
        $name = $fullName !== '' ? $fullName : 'Participante';

        $nameCenterX = (int) round($canvasWidth * ($this->coordinate($evento, 'name', 'x') / 100));
        $nameCenterY = (int) round($canvasHeight * ($this->coordinate($evento, 'name', 'y') / 100));
        $nameMaxWidth = (int) round($canvasWidth * 0.84);
        $this->drawCenteredSingleLineText($image, $name, $nameCenterX, $nameCenterY, $nameMaxWidth);

        $qrSizePercent = max(12, min(28, (int) ($design['qr_size'] ?? 18)));
        $qrPixelSize = (int) round($canvasWidth * ($qrSizePercent / 100));
        $qrCenterX = (int) round($canvasWidth * ($this->coordinate($evento, 'qr', 'x') / 100));
        $qrCenterY = (int) round($canvasHeight * ($this->coordinate($evento, 'qr', 'y') / 100));
        $qrX = (int) round($qrCenterX - ($qrPixelSize / 2));
        $qrY = (int) round($qrCenterY - ($qrPixelSize / 2));

        $this->placeQrOnCanvas($image, $this->code($registration), $qrX, $qrY, $qrPixelSize);

        $caption = 'Acceso participante';
        $captionY = (int) min($canvasHeight - 18, round($qrY + $qrPixelSize + max(24, $canvasHeight * 0.02)));
        $this->drawCenteredCaption($image, $caption, $qrCenterX, $captionY, max($qrPixelSize, 220));

        ob_start();
        imagepng($image);
        $binary = (string) ob_get_clean();
        imagedestroy($image);

        return $binary;
    }

    private function design(Evento $evento): ?array
    {
        return ($evento->graphic_designs ?? [])['gafete_participante'] ?? null;
    }

    private function coordinate(Evento $evento, string $element, string $axis): float
    {
        $design = $this->design($evento) ?? [];
        $default = $element === 'qr'
            ? ['x' => 50, 'y' => 79]
            : ['x' => 50, 'y' => 58];

        if (array_key_exists($element . '_x', $design) && array_key_exists($element . '_y', $design)) {
            return (float) max(0, min(100, (float) ($design[$element . '_' . $axis] ?? $default[$axis])));
        }

        return (float) $default[$axis];
    }

    private function canvasDimensions(?string $pageSize, ?string $orientation, ?int $targetCanvasWidth = null): array
    {
        $ratio = self::PAGE_SIZE_RATIOS[$pageSize ?: ''] ?? self::PAGE_SIZE_RATIOS['Carta'];
        [$width, $height] = $ratio;

        if (($orientation ?: 'Vertical') === 'Horizontal') {
            [$width, $height] = [$height, $width];
        }

        $canvasWidth = max(900, (int) ($targetCanvasWidth ?? 1600));
        $canvasHeight = (int) round(($height / $width) * $canvasWidth);

        return [$canvasWidth, $canvasHeight];
    }

    private function templateFilesystemPath(Evento $evento): ?string
    {
        $file = $this->design($evento)['file'] ?? null;

        if (! $file) {
            return null;
        }

        $path = public_path($file);

        return file_exists($path) ? $path : null;
    }

    private function makeCanvas(Evento $evento, int $canvasWidth, int $canvasHeight)
    {
        $image = imagecreatetruecolor($canvasWidth, $canvasHeight);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $backgroundPath = $this->templateFilesystemPath($evento);
        $background = $backgroundPath ? $this->loadRasterImage($backgroundPath) : null;

        if ($background) {
            imagecopyresampled(
                $image,
                $background,
                0,
                0,
                0,
                0,
                $canvasWidth,
                $canvasHeight,
                imagesx($background),
                imagesy($background)
            );
            imagedestroy($background);

            return $image;
        }

        $ink = imagecolorallocate($image, 15, 23, 42);
        $violet = imagecolorallocate($image, 124, 92, 255);
        $violetSoft = imagecolorallocate($image, 245, 243, 255);
        $cyanSoft = imagecolorallocate($image, 224, 242, 254);
        $white = imagecolorallocate($image, 255, 255, 255);
        $soft = imagecolorallocate($image, 248, 250, 252);
        $line = imagecolorallocate($image, 203, 213, 225);

        imagefilledrectangle($image, 0, 0, $canvasWidth, $canvasHeight, $white);
        imagefilledellipse($image, (int) round($canvasWidth * 0.14), (int) round($canvasHeight * 0.12), (int) round($canvasWidth * 0.3), (int) round($canvasWidth * 0.3), $violetSoft);
        imagefilledellipse($image, (int) round($canvasWidth * 0.86), (int) round($canvasHeight * 0.9), (int) round($canvasWidth * 0.38), (int) round($canvasWidth * 0.38), $cyanSoft);
        imagefilledrectangle($image, (int) round($canvasWidth * 0.07), (int) round($canvasHeight * 0.18), (int) round($canvasWidth * 0.93), (int) round($canvasHeight * 0.93), $soft);
        imagerectangle($image, (int) round($canvasWidth * 0.07), (int) round($canvasHeight * 0.18), (int) round($canvasWidth * 0.93), (int) round($canvasHeight * 0.93), $line);

        $fontPath = $this->fontPath();
        if ($fontPath) {
            imagettftext(
                $image,
                max(20, (int) round($canvasHeight * 0.024)),
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.1),
                $violet,
                $fontPath,
                'PARTICIPANTE'
            );

            $eventName = Str::limit((string) $evento->nombreevento, 42, '...');
            $eventFontSize = $this->fittedFontSize($eventName, $fontPath, (int) round($canvasWidth * 0.76), 30, 16);
            $eventBox = imagettfbbox($eventFontSize, 0, $fontPath, $eventName);
            $eventHeight = (int) abs($eventBox[7] - $eventBox[1]);
            imagettftext(
                $image,
                $eventFontSize,
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.16) + $eventHeight,
                $ink,
                $fontPath,
                $eventName
            );

            imagettftext(
                $image,
                max(14, (int) round($canvasHeight * 0.016)),
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.15),
                $ink,
                $fontPath,
                'Gafete oficial de acceso al evento'
            );
        }

        return $image;
    }

    private function loadRasterImage(string $path)
    {
        $mime = mime_content_type($path) ?: '';

        return match ($mime) {
            'image/png' => @imagecreatefrompng($path),
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => @imagecreatefromstring((string) file_get_contents($path)),
        };
    }

    private function drawCenteredSingleLineText($image, string $text, int $centerX, int $centerY, int $maxWidth, ?int $maxFontSize = null, int $minFontSize = 26): void
    {
        $fontPath = $this->fontPath();
        $text = trim($text) !== '' ? trim($text) : 'Participante';
        $color = imagecolorallocate($image, 15, 23, 42);

        if (! $fontPath) {
            $font = 5;
            $width = imagefontwidth($font) * strlen($text);
            $height = imagefontheight($font);
            imagestring($image, $font, (int) round($centerX - ($width / 2)), (int) round($centerY - ($height / 2)), $text, $color);

            return;
        }

        $fontSize = $this->fittedFontSize($text, $fontPath, $maxWidth, $maxFontSize ?? 96, $minFontSize);
        $box = imagettfbbox($fontSize, 0, $fontPath, $text);
        $minX = min($box[0], $box[2], $box[4], $box[6]);
        $maxX = max($box[0], $box[2], $box[4], $box[6]);
        $minY = min($box[1], $box[3], $box[5], $box[7]);
        $maxY = max($box[1], $box[3], $box[5], $box[7]);
        $textWidth = (int) round($maxX - $minX);
        $textHeight = (int) round($maxY - $minY);
        $x = (int) round($centerX - ($textWidth / 2) - $minX);
        $y = (int) round($centerY + ($textHeight / 2) - $maxY);

        imagettftext($image, $fontSize, 0, $x, $y, $color, $fontPath, $text);
    }

    private function drawCenteredCaption($image, string $text, int $centerX, int $baselineY, int $maxWidth): void
    {
        $fontPath = $this->fontPath();
        $color = imagecolorallocate($image, 15, 23, 42);

        if (! $fontPath) {
            $font = 3;
            $width = imagefontwidth($font) * strlen($text);
            imagestring($image, $font, (int) round($centerX - ($width / 2)), $baselineY, $text, $color);

            return;
        }

        $fontSize = $this->fittedFontSize($text, $fontPath, $maxWidth, 28, 14);
        $box = imagettfbbox($fontSize, 0, $fontPath, $text);
        $minX = min($box[0], $box[2], $box[4], $box[6]);
        $maxX = max($box[0], $box[2], $box[4], $box[6]);
        $textWidth = (int) round($maxX - $minX);
        imagettftext($image, $fontSize, 0, (int) round($centerX - ($textWidth / 2) - $minX), $baselineY, $color, $fontPath, $text);
    }

    private function placeQrOnCanvas($image, string $code, int $x, int $y, int $size): void
    {
        $qrBase64 = QRCodeService::generateTextQRCode($code, max(280, $size * 3));
        $qrImage = @imagecreatefromstring(base64_decode($qrBase64));

        if (! $qrImage) {
            return;
        }

        imagecopyresampled(
            $image,
            $qrImage,
            $x,
            $y,
            0,
            0,
            $size,
            $size,
            imagesx($qrImage),
            imagesy($qrImage)
        );

        imagedestroy($qrImage);
    }

    private function fittedFontSize(string $text, string $fontPath, int $maxWidth, int $start, int $min): int
    {
        for ($size = $start; $size >= $min; $size--) {
            $box = imagettfbbox($size, 0, $fontPath, $text);
            $width = (int) abs($box[2] - $box[0]);

            if ($width <= $maxWidth) {
                return $size;
            }
        }

        return $min;
    }

    private function fontPath(): ?string
    {
        $candidates = [
            '/System/Library/Fonts/Supplemental/Arial Bold.ttf',
            '/Library/Fonts/Arial Unicode.ttf',
            public_path('fonts/GreatVibes-Regular.ttf'),
        ];

        foreach ($candidates as $path) {
            if ($path && file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
