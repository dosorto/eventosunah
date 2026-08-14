<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use App\Livewire\Evento\GestionarEventoPublicado;
use App\Models\Evento;
use App\Models\EventoDocumentoProceso;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('evento:process-document-tasks {eventoId}', function (int $eventoId) {
    $runningCacheKey = 'evento-document-task-running-' . $eventoId;

    if (! Cache::add($runningCacheKey, now()->timestamp, now()->addMinutes(3))) {
        $this->comment('Ya existe un proceso ejecutándose para este evento.');

        return self::SUCCESS;
    }

    try {
        ignore_user_abort(true);
        @set_time_limit(0);
        @ini_set('memory_limit', '1024M');

        $evento = Evento::query()->findOrFail($eventoId);
        /** @var GestionarEventoPublicado $component */
        $component = app(GestionarEventoPublicado::class);
        $component->evento = $evento;

        $iterations = 0;

        while (EventoDocumentoProceso::query()
            ->where('evento_id', $eventoId)
            ->whereIn('estado', ['pendiente', 'procesando'])
            ->exists()) {
            Cache::put($runningCacheKey, now()->timestamp, now()->addMinutes(3));
            $component->processBackgroundDocumentTasks();
            $iterations++;

            if ($iterations >= 10000) {
                break;
            }

            usleep(150000);
        }

        return self::SUCCESS;
    } finally {
        Cache::forget($runningCacheKey);
    }
})->purpose('Procesa en segundo plano los lotes de documentos generados para un evento.');
