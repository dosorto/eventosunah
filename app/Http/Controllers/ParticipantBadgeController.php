<?php

namespace App\Http\Controllers;

use App\Models\EventoRegistro;
use App\Services\ParticipantBadgeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParticipantBadgeController extends Controller
{
    public function download(Request $request, EventoRegistro $registro, ParticipantBadgeService $badgeService): Response
    {
        $registro->loadMissing('evento', 'persona');

        $user = $request->user();
        $isOwner = (int) ($user?->persona?->id ?? 0) === (int) $registro->persona_id;
        $canManage = (bool) $user?->can('events.manage');

        abort_unless($isOwner || $canManage, 403);
        abort_unless($badgeService->isAvailable($registro), 403, 'El gafete aún no está disponible.');

        $png = $badgeService->renderPng($registro);
        $filename = $badgeService->filename($registro);

        if ($request->boolean('inline')) {
            return response($png, 200, [
                'Content-Type' => 'image/png',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
            ]);
        }

        return response()->streamDownload(function () use ($png): void {
            echo $png;
        }, $filename, [
            'Content-Type' => 'image/png',
        ]);
    }
}
