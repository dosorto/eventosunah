<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use App\Models\Asistencia;

class EventoVistaController extends Controller
{
    public function showConferencias(Evento $evento)
    {
        // Obtener las conferencias relacionadas con el evento
        $conferencias = $evento->conferencias()->paginate(6);

        return view('livewire.VistaConferencia.vista-conferencia', compact('evento', 'conferencias'));
    }

    public function showDiplomas(Asistencia $asistencia)
    {
        // retornar la vista diplomas.validacion
    }
}