<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoVistaController extends Controller
{
    public function showConferencias(Evento $evento)
    {
        // Obtener las conferencias relacionadas con el evento
        $conferencias = $evento->conferencias()->paginate(9);

        return view('livewire.VistaConferencia.vista-conferencia', compact('evento', 'conferencias'));
    }
}