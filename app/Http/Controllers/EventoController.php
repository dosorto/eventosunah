<?php

namespace App\Http\Controllers;

use App\Models\Conferencia;
use App\Models\Evento;
use App\Models\EventoRegistro;
use App\Models\MetodoPago;
use Carbon\Carbon;

class EventoController extends Controller
{
    private const FIXED_PAYMENT_CODES = [
        'efectivo',
        'transferencia',
        'tarjeta_online',
    ];

    public function show($id)
    {
        $viewer = auth()->user()?->loadMissing('persona.tipoPerfil');
        $viewerProfileId = $viewer?->persona?->IdTipoPerfil;
        $viewerPersonaId = $viewer?->persona?->id;

        $query = Evento::query()->with([
            'modalidad',
            'localidad',
            'precios.tipoPerfil',
            'precios.moneda',
            'conferencias.tipoConferencia',
            'conferencias.speakerPersona.nacionalidad',
            'conferencias.conferencista.persona.nacionalidad',
        ]);

        if (! auth()->check() || ! auth()->user()->can('events.manage')) {
            $query->published();
        }

        $evento = $query->findOrFail($id);
        $today = Carbon::now()->toDateString();
        $allPriceDisplay = $evento->precios
            ->where('es_precio_evento_dia', true)
            ->map(function ($eventDayPrice) use ($evento, $today) {
                $activeRange = $evento->precios
                    ->where('IdTipoPerfil', $eventDayPrice->IdTipoPerfil)
                    ->where('es_precio_evento_dia', false)
                    ->first(function ($rangePrice) use ($today) {
                        return $rangePrice->fecha_inicio?->format('Y-m-d') <= $today
                            && $rangePrice->fecha_fin?->format('Y-m-d') >= $today;
                    });

                return [
                    'profile_name' => $eventDayPrice->tipoPerfil?->tipoperfil ?? 'Tipo de perfil',
                    'profile_id' => $eventDayPrice->IdTipoPerfil,
                    'currency_symbol' => $eventDayPrice->moneda?->simbolo,
                    'currency_code' => $eventDayPrice->moneda?->codigo,
                    'base_amount' => (float) $eventDayPrice->precio,
                    'active_amount' => $activeRange ? (float) $activeRange->precio : null,
                    'offer_label' => $activeRange?->categoria_nombre ?: 'Oferta vigente',
                    'offer_dates' => $activeRange
                        ? sprintf(
                            '%s - %s',
                            $activeRange->fecha_inicio?->format('d/m/Y'),
                            $activeRange->fecha_fin?->format('d/m/Y')
                        )
                        : null,
                ];
            })
            ->values();

        $priceDisplay = $viewerProfileId
            ? $allPriceDisplay->where('profile_id', $viewerProfileId)->values()
            : $allPriceDisplay;

        $featuredPrice = $priceDisplay->first() ?? $allPriceDisplay->first();
        $existingRegistration = $viewerPersonaId
            ? EventoRegistro::query()
                ->where('evento_id', $evento->id)
                ->where('persona_id', $viewerPersonaId)
                ->first()
            : null;

        $metodosPago = $evento->tipo_acceso === 'pagada'
            ? MetodoPago::query()
                ->active()
                ->whereIn('codigo', self::FIXED_PAYMENT_CODES)
                ->where(function ($query) {
                    $query
                        ->where('tipo', MetodoPago::TYPE_CASH)
                        ->orWhere(function ($transferQuery) {
                            $transferQuery
                                ->where('tipo', MetodoPago::TYPE_TRANSFER)
                                ->whereNotNull('banco_nombre')
                                ->whereNotNull('numero_cuenta')
                                ->whereNotNull('titular_cuenta');
                        })
                        ->orWhere('tipo', MetodoPago::TYPE_CARD);
                })
                ->orderBy('orden')
                ->orderBy('nombre')
                ->get()
            : collect();

        $conferencias = Conferencia::query()
            ->with(['tipoConferencia', 'speakerPersona.nacionalidad', 'conferencista.persona.nacionalidad'])
            ->where('IdEvento', $id)
            ->orderBy('fecha')
            ->orderBy('horaInicio')
            ->get();

        $agendaDays = [];
        $startDate = $evento->fechainicio?->copy();
        $endDate = ($evento->fechafinal ?? $evento->fechainicio)?->copy();

        if ($startDate && $endDate) {
            $cursor = $startDate->copy();
            $dayIndex = 1;

            while ($cursor->lte($endDate)) {
                $dateKey = $cursor->format('Y-m-d');

                $agendaDays[] = [
                    'index' => $dayIndex,
                    'date' => $dateKey,
                    'label' => 'Día ' . $dayIndex,
                    'display' => $cursor->format('d/m/Y'),
                    'conferences' => $conferencias
                        ->where('fecha', $dateKey)
                        ->sortBy('horaInicio')
                        ->values(),
                ];

                $cursor->addDay();
                $dayIndex++;
            }
        }

        return view('evento', compact('evento', 'conferencias', 'priceDisplay', 'allPriceDisplay', 'featuredPrice', 'agendaDays', 'viewerProfileId', 'metodosPago', 'existingRegistration'));
    }
}
