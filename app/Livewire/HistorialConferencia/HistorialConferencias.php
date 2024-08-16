<?php

namespace App\Livewire\HistorialConferencia;

use GuzzleHttp\Psr7\Message;
use Livewire\Component;
use App\Models\Asistencia;
use Illuminate\Support\Facades\Auth;
use Redirect;

class HistorialConferencias extends Component
{
    public $conferencias = [];

    public function mount()
    {
        $this->loadConferencias();
    }

    public function loadConferencias()
    {
        $personaId = Auth::user()->persona->id;

        // Filtrar asistencias donde la asistencia es 1
        $suscripciones = Asistencia::where('asistencia', 1) // Solo asistencias marcadas como 1
            ->whereHas('suscripcion', function($query) use ($personaId) {
                $query->where('IdPersona', $personaId);
            })
            ->with('suscripcion.conferencia')
            ->get()
            ->map(function ($asistencia) {
                return $asistencia->suscripcion->conferencia;
            })
            ->unique('id');
        
        $this->conferencias = $suscripciones;
    }

    public function generarDiploma($IdAsistencia)
    {
        // Buscar la asistencia correspondiente al IdAsistencia
        $asistencia = Asistencia::find($IdAsistencia);

        if ($asistencia) {
            // Generar un UUID para el diploma
            $uuidDiploma = Str::uuid();

            // Crear una nueva entrada en la tabla DiplomaGenerado
            $diplomaGenerado = new DiplomaGenerado();
            $diplomaGenerado->IdAsistencia = $IdAsistencia;
            $diplomaGenerado->uuid = $uuidDiploma;

            // Guardar la entrada en la base de datos
            $diplomaGenerado->save();
        } else {
            // Manejar el caso en que no se encuentre la asistencia
            // Puedes lanzar una excepción o manejar el error de otra manera
            throw new Exception('No se encontró la asistencia correspondiente al IdAsistencia');
        }
    }


    public function render()
    {
        return view('livewire.HistorialConferencia.historial-conferencias', [
            'conferencias' => $this->conferencias,
        ]);
    }
}

