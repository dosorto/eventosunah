<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Conferencia;
use App\Models\Persona;
use App\Models\Asistencia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener la fecha y hora actual
        $now = Carbon::now();

        // contar la cantidad de eventos que hay en la base de datos
        $cantidadEventos = Evento::count();

        $conferenciass = Suscripcion::withCount('conferencia')->get();

        // ordenar las conferencias por fecha y seleccionar las primeras 10
        $conferencias = Conferencia::orderBy('fecha', 'desc')->take(5)->get();

        // Contar los eventos por modalidad 'Presencial' y 'Virtual'
        $eventosPresenciales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Presencial')
            ->count();

        $eventosVirtuales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Virtual')
            ->count();

        // Contar los eventos activos y finalizados basados en la fecha actual
        $eventosActivos = Evento::where('fecha', '>=', $now)->count();
        $eventosFinalizados = Evento::where('fecha', '<', $now)->count();

        return view('dashboard', [
            'cantidadEventos' => $cantidadEventos,
            'eventosActivos' => $eventosActivos,
            'eventosFinalizados' => $eventosFinalizados,
            'eventosPresenciales' => $eventosPresenciales,
            'eventosVirtuales' => $eventosVirtuales,
            'conferencias' => $conferencias,
            'now' => $now,
            'conferenciass' => $conferenciass
        ]);
    }
}
