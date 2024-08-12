<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use Illuminate\Http\Request;

use App\Models\Evento;
use App\Models\Conferencia;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $now = Carbon::now();

        // contar la cantidad de eventos que hay en la base de datos
        $cantidadEventos = Evento::count();

        $conferenciass =  Conferencia::withCount(['suscripciones as unique_subscriptions' => function ($query) {
            $query->select(DB::raw('count(distinct IdPersona)'));
        }])
        ->having('unique_subscriptions', '>', 0)
        ->get();

        // ordenar las conferencias por fecha fecha y seleccionar las primeras 10
        $conferencias = Conferencia::orderBy('fecha', 'desc')->take(5)->get();


        // contar los eventos por modalidad 'Presencial', 'Virtual'
        $eventosPresenciales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Presencial')
            ->count();

        $eventosVirtuales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Virtual')
            ->count();

        

        return view('dashboard', [
            'cantidadEventos' => $cantidadEventos,
            'eventosPresenciales' => $eventosPresenciales,
            'eventosVirtuales' => $eventosVirtuales,
            'conferencias' => $conferencias,
            'now' => $now,
            'conferenciass' => $conferenciass

            
        ]);
    }
}
