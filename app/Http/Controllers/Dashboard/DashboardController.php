<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use Illuminate\Http\Request;

use App\Models\Evento;
use App\Models\Conferencia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $now = Carbon::now();

        // contar la cantidad de eventos que hay en la base de datos
        $cantidadEventos = Evento::count();

        $conferenciass = Suscripcion::withCount('conferencia')->get();

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
