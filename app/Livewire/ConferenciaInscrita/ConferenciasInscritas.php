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
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;
    public function desuscribirse($id)
    {
        $suscripcion = Suscripcion::where('IdConferencia', $id)->first();
    
        if (!$suscripcion) {
            session()->flash('error', 'No se pudo encontrar la inscripción.');
            return;
        }
    
        if ($suscripcion->asistencias()->exists()) {
            session()->flash('error', 'No se puede eliminar la inscripción porque está enlazada a una o más asistencias.');
            return;
        }
    
        $this->IdAEliminar = $suscripcion->id;
        $this->nombreAEliminar = $suscripcion->id; // o cualquier campo relevante
        $this->confirmingDelete = true;
    }
    
    public function deleteSuscripcion()
    {
        if ($this->confirmingDelete) {
            $suscripcion = Suscripcion::find($this->IdAEliminar);
    
            if (!$suscripcion) {
                session()->flash('error', 'Suscripción no encontrada.');
                $this->confirmingDelete = false;
                return;
            }
    
            $suscripcion->delete();
            session()->flash('success', 'Eliminaste la inscripción a la conferencia.');
            $this->confirmingDelete = false;
    
            return redirect()->route('conferencias-inscritas'); 
        }
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