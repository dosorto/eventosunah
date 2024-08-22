<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Evento;
use App\Models\Conferencia;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Contar la cantidad de eventos en la base de datos
        $cantidadEventos = Evento::count();

        // Contar la cantidad de eventos que han finalizado
        $eventosFinalizados = Evento::where('fechafinal', '<', Carbon::now())->count();

        $conferenciass = Conferencia::withCount(['suscripciones as unique_subscriptions' => function ($query) {
            $query->select(DB::raw('count(distinct IdPersona)'));
        }])
        ->having('unique_subscriptions', '>', 0)
        ->get();

        // Ordenar las conferencias por fecha y seleccionar las primeras 10
        $conferencias = Conferencia::orderBy('fecha', 'desc')->take(5)->get();

        // Contar los eventos por modalidad 'Presencial' y 'Virtual'
        $eventosPresenciales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Presencial')
            ->count();

        $eventosVirtuales = Evento::join('modalidads', 'eventos.idmodalidad', '=', 'modalidads.id')
            ->where('modalidads.modalidad', 'Virtual')
            ->count();

        // Contar el total de usuarios registrados
        $totalUsuarios = User::count();

        return view('dashboard', [
            'cantidadEventos' => $cantidadEventos,
            'eventosFinalizados' => $eventosFinalizados,
            'eventosPresenciales' => $eventosPresenciales,
            'eventosVirtuales' => $eventosVirtuales,
            'totalUsuarios' => $totalUsuarios, 
            'conferencias' => $conferencias,
            'now' => $now,
            'conferenciass' => $conferenciass
        ]);
    }
}