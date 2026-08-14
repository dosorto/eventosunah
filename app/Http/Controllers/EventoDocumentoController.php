<?php

namespace App\Http\Controllers;

use App\Models\EventoDocumentoProceso;
use Illuminate\Http\Request;

class EventoDocumentoController extends Controller
{
    public function download(Request $request, EventoDocumentoProceso $proceso)
    {
        abort_unless($request->user()?->can('events.manage'), 403);

        if ($proceso->estado !== 'completado' || ! $proceso->ruta_salida) {
            abort(404);
        }

        $absolutePath = storage_path('app/' . $proceso->ruta_salida);

        if (! file_exists($absolutePath)) {
            abort(404);
        }

        return response()->streamDownload(function () use ($absolutePath): void {
            $handle = fopen($absolutePath, 'rb');

            if ($handle === false) {
                return;
            }

            while (! feof($handle)) {
                echo fread($handle, 1024 * 1024);
                flush();
            }

            fclose($handle);
        }, basename($absolutePath), [
            'Content-Type' => 'application/pdf',
            'Content-Length' => (string) filesize($absolutePath),
        ]);
    }
}
