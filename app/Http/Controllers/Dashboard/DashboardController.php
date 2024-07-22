<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Evento;

class DashboardController extends Controller
{
    //

    public function index()
    {

        // contar la cantidad de eventos que hay en la base de datos
        $cantidadEventos = Evento::count();


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
            'eventosVirtuales' => $eventosVirtuales
        ]);
    }
}
