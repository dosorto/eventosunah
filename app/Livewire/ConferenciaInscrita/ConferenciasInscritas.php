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
    public $confirmCancelacion = false;
    public $errorCancelacion = false;
    public $IdAConfirmarCancelacion;
    public $nombreAConfirmarAsistencia;
    public $nombreAConfirmarCancelacion;
    public $nombreAEliminar;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $modoHistorial = false; 

    public function mount($modoHistorial = false)
    {
        $this->modoHistorial = $modoHistorial;
        $IdUsuario = Auth::id();
        $persona = Persona::where('IdUsuario', $IdUsuario)->first();
    
        if ($persona) {
            $IdPersona = $persona->id;

            if ($this->modoHistorial) {
                // Filtrar conferencias donde la asistencia es verdadera (1 == presente)
                $this->conferencias = Suscripcion::where('IdPersona', $IdPersona)
                    ->whereHas('asistencias', function ($query) {
                        $query->where('asistencia', 1); // Asistencia marcada como 1
                    })
                    ->with('conferencia')
                    ->get();
            } else {
                // Filtrar conferencias donde no se ha marcado asistencia (ausente o sin asistencia)
                $this->conferencias = Suscripcion::where('IdPersona', $IdPersona)
                    ->whereDoesntHave('asistencias', function ($query) {
                        $query->where('asistencia', 1); // Excluir asistencia marcada como 1
                    })
                    ->with('conferencia')
                    ->get();
            }
        } else {
            $this->conferencias = collect();
        }
    }
    
    public function desuscribirse($id)
    {
        $this->IdAConfirmarCancelacion = $id;
        $this->confirmCancelacion = true;

        $suscripcion = Suscripcion::where('id', $id)
            ->where('IdPersona', Auth::id())
            ->first();
        
        if (!$suscripcion) {
            session()->flash('error', 'No se pudo encontrar la inscripción.');
            return;
        }

        $this->nombreAConfirmarCancelacion = $suscripcion->conferencia->nombre;
        $this->IdAEliminar = $suscripcion->id;
        $this->nombreAEliminar = $suscripcion->conferencia->nombre;
        $this->confirmingDelete = true;
    }

    public function confirmarCancelacion()
    {
        $suscripcion = Suscripcion::where('id', $this->IdAConfirmarCancelacion)
            ->where('IdPersona', Auth::id())
            ->first();

        if (!$suscripcion) {
            session()->flash('error', 'No se pudo encontrar la inscripción.');
            $this->confirmCancelacion = false;
            return;
        }

        // Elimina la suscripción
        $suscripcion->delete();
        session()->flash('success', 'Eliminaste la inscripción a la conferencia.');
        $this->confirmCancelacion = false;

        // Recarga las conferencias después de la eliminación
        $this->mount($this->modoHistorial);
    }
    
    public function render()
    {
        return view('livewire.ConferenciaInscrita.conferencias-inscritas', [
            'conferencias' => $this->conferencias,
            'modoHistorial' => $this->modoHistorial,
        ]);
    }
}

