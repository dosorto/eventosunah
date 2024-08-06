<?php

namespace App\Livewire\ConferenciaInscrita;

use App\Models\Asistencia;
use Livewire\Component;
use App\Models\Suscripcion;
use Illuminate\Support\Facades\Auth;
use App\Models\Persona;

class ConferenciasInscritas extends Component
{
    public $conferencias;
    public $asistenciaMarcada = [];
    public function mount()
    {
        // Obtener el IdPersona del usuario actual
        $IdUsuario = Auth::id();
        $persona = Persona::where('IdUsuario', $IdUsuario)->first();

        if ($persona) {
            $IdPersona = $persona->id;

            // Obtener las conferencias inscritas
            $this->conferencias = Suscripcion::where('IdPersona', $IdPersona)
                ->with('conferencia')
                ->get();
        } else {
            $this->conferencias = collect(); // Colección vacía si no se encuentra la persona
        }
    }

    public function desuscribirse($id)
    {
        $suscripcion = Suscripcion::where('IdConferencia', $id)->first();
        if ($suscripcion) {
            $suscripcion->delete();
            session()->flash('success', 'Eliminaste la inscripción a la conferencia.');
        } else {
            session()->flash('error', 'No se pudo eliminar la inscripcion.');
        }
        return redirect()->route('conferencias-inscritas'); // Reemplaza 'nombre_de_tu_ruta' con la ruta correcta
    }


    public function asistencia($IdSuscripcion)
    {
        if (Asistencia::where('IdSuscripcion', $IdSuscripcion)->exists()) {
            session()->flash('error', 'Ya has marcado asistencia a esta conferencia.');
            return;
        }

        Asistencia::updateOrCreate([
            'Fecha' => now(),
            'Asistencia' => true,
            'IdSuscripcion' => $IdSuscripcion,
            'created_by' => Auth::id()
        ]);
        $this->asistenciaMarcada[$IdSuscripcion] = true;

        session()->flash('success', 'Asistencia marcada correctamente.');
    }

    public function render()
    {
        return view('livewire.ConferenciaInscrita.conferencias-inscritas', [
            'conferencias' => $this->conferencias,
        ]);
    }
}