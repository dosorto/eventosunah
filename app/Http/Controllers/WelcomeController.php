<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $search = trim((string) $request->string('q'));
        $selectedType = trim((string) $request->string('tipo'));
        $displayMode = trim((string) $request->string('vista', 'card'));
        $displayMode = in_array($displayMode, ['card', 'list'], true) ? $displayMode : 'card';

        $baseQuery = Evento::query()
            ->with(['modalidad', 'localidad', 'tipoEvento'])
            ->withCount('conferencias')
            ->published()
            ->when($search !== '', function ($builder) use ($search) {
                $builder->where(function ($eventQuery) use ($search) {
                    $eventQuery
                        ->where('nombreevento', 'like', '%' . $search . '%')
                        ->orWhere('descripcion', 'like', '%' . $search . '%')
                        ->orWhere('organizador', 'like', '%' . $search . '%')
                        ->orWhere('localidad_nombre', 'like', '%' . $search . '%')
                        ->orWhereHas('localidad', fn ($localidadQuery) => $localidadQuery->where('localidad', 'like', '%' . $search . '%'))
                        ->orWhereHas('tipoEvento', fn ($typeQuery) => $typeQuery->where('tipo', 'like', '%' . $search . '%'));
                });
            })
            ->when($selectedType !== '' && $selectedType !== 'todos', function ($builder) use ($selectedType) {
                $builder->whereHas('tipoEvento', fn ($typeQuery) => $typeQuery->where('tipo', $selectedType));
            });

        $Eventos = (clone $baseQuery)
            ->orderByRaw(
                "case
                    when fechainicio >= ? then 0
                    when fechainicio < ? and (fechafinal is null or fechafinal >= ?) then 1
                    else 2
                end asc",
                [$today, $today, $today]
            )
            ->orderByRaw(
                "case
                    when fechainicio >= ? then fechainicio
                end asc",
                [$today]
            )
            ->orderByRaw(
                "case
                    when fechainicio < ? and (fechafinal is null or fechafinal >= ?) then fechainicio
                end asc",
                [$today, $today]
            )
            ->orderByRaw(
                "case
                    when fechafinal < ? then fechainicio
                end desc",
                [$today]
            )
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        $availableTypes = Evento::query()
            ->published()
            ->with('tipoEvento:id,tipo')
            ->get()
            ->pluck('tipoEvento.tipo')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('welcome', [
            'Eventos' => $Eventos,
            'availableTypes' => $availableTypes,
            'selectedType' => $selectedType !== '' ? $selectedType : 'todos',
            'search' => $search,
            'displayMode' => $displayMode,
        ]);
    }
}
