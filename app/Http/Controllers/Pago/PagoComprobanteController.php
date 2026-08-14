<?php

namespace App\Http\Controllers\Pago;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\EventoRegistro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PagoComprobanteController extends Controller
{
    public function show(Evento $evento, EventoRegistro $registro): StreamedResponse
    {
        abort_unless(Auth::user()?->can('events.manage'), 403);
        abort_unless((int) $registro->evento_id === (int) $evento->id, 404);
        abort_unless(filled($registro->comprobante_pago_path), 404);

        $path = ltrim((string) $registro->comprobante_pago_path, '/');
        $publicDisk = Storage::disk('public');
        $disk = Storage::disk('local');

        if ($publicDisk->exists($path)) {
            return $publicDisk->response(
                $path,
                $registro->comprobante_pago_nombre ?: basename($path),
                array_filter([
                    'Content-Type' => $registro->comprobante_pago_mime ?: null,
                ])
            );
        }

        if ($disk->exists($path)) {
            return $disk->response(
                $path,
                $registro->comprobante_pago_nombre ?: basename($path),
                array_filter([
                    'Content-Type' => $registro->comprobante_pago_mime ?: null,
                ])
            );
        }

        if (str_starts_with($path, 'public/')) {
            $legacyPath = substr($path, 7);

            if ($legacyPath !== false && $publicDisk->exists($legacyPath)) {
                return $publicDisk->response(
                    $legacyPath,
                    $registro->comprobante_pago_nombre ?: basename($legacyPath),
                    array_filter([
                        'Content-Type' => $registro->comprobante_pago_mime ?: null,
                    ])
                );
            }

            if ($legacyPath !== false && $disk->exists($legacyPath)) {
                return $disk->response(
                    $legacyPath,
                    $registro->comprobante_pago_nombre ?: basename($legacyPath),
                    array_filter([
                        'Content-Type' => $registro->comprobante_pago_mime ?: null,
                    ])
                );
            }
        }

        abort(404);
    }
}
