<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
        // contar la cantidad de eventos que hay en la base de datos
        $cantidadEventos = Evento::count();

        // Contar la cantidad de eventos activos y finalizados
        $fechaActual = Carbon::now();
        $eventosActivos = Evento::where('fechafinal', '>=', $fechaActual)->count();
        $eventosFinalizados = Evento::where('fechafinal', '<', $fechaActual)->count();

        // Ordenar las conferencias por fecha y seleccionar las primeras 5
        $conferencias = Conferencia::orderBy('fecha', 'desc')->take(5)->get();

        // Contar los eventos por modalidad 'Presencial' y 'Virtual'
        $eventosPresenciales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Presencial')
            ->count();

        $eventosVirtuales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Virtual')
            ->count();


        return view('dashboard', [
            'cantidadEventos' => $cantidadEventos,
            'eventosActivos' => $eventosActivos,
            'eventosFinalizados' => $eventosFinalizados,
            'eventosPresenciales' => $eventosPresenciales,
            'eventosVirtuales' => $eventosVirtuales,
            'conferencias' => $conferencias,
            'now' => $now,
            
        ]);
    }
}
