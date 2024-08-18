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
    public $confirmAsistencia = false;
    public $confirmCancelacion = false;
    public $errorCancelacion = false;
    public $asistenciaYaMarcada = false;
    public $IdAConfirmarAsistencia;
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
                
                $this->conferencias = Suscripcion::where('IdPersona', $IdPersona)
                    ->whereHas('asistencias', function ($query) {
                        $query->where('asistencia', true); 
                    })
                    ->with('conferencia')
                    ->get();
            } else {
                $this->conferencias = Suscripcion::where('IdPersona', $IdPersona)
                    ->whereDoesntHave('asistencias')
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

        // Busca la suscripción usando 'Id' en lugar de 'IdConferencia'
        $suscripcion = Suscripcion::where('id', $id) // Cambié 'IdConferencia' a 'id'
            ->where('IdPersona', Auth::id()) // Asegúrate de que la suscripción pertenezca al usuario actual
            ->first();
        
        if (!$suscripcion) {
            session()->flash('error', 'No se pudo encontrar la inscripción.');
            return;
        }

        $this->nombreAConfirmarCancelacion = $suscripcion->conferencia->nombre;
        $this->IdAEliminar = $suscripcion->id;
        $this->nombreAEliminar = $suscripcion->conferencia->nombre; // Usa el nombre de la conferencia
        $this->confirmingDelete = true;
    }

    public function confirmarCancelacion()
    {
        $suscripcion = Suscripcion::where('id', $this->IdAConfirmarCancelacion) // Cambié 'IdConferencia' a 'id'
            ->where('IdPersona', Auth::id()) // Asegúrate de que la suscripción pertenezca al usuario actual
            ->first();

        if (!$suscripcion) {
            session()->flash('error', 'No se pudo encontrar la inscripción.');
            $this->confirmCancelacion = false;
            return;
        }

        // Elimina la suscripción sin verificar asistencias
        $suscripcion->delete();
        session()->flash('success', 'Eliminaste la inscripción a la conferencia.');
        $this->confirmCancelacion = false;

        // Recarga las conferencias después de la eliminación
        $this->mount($this->modoHistorial);
    }

    public function asistencia($id)
    {
        $this->IdAConfirmarAsistencia = $id;

        if (Asistencia::where('IdSuscripcion', $id)->exists()) {
            $this->asistenciaYaMarcada = true;
            $this->confirmAsistencia = false;
        } else {
            $this->confirmAsistencia = true;
            $this->asistenciaYaMarcada = false;
        }

        $suscripcion = Suscripcion::find($id);
        if ($suscripcion) {
            $this->nombreAConfirmarAsistencia = $suscripcion->conferencia->nombre;
        } else {
            $this->nombreAConfirmarAsistencia = '';
        }
    }

    public function confirmarAsistencia()
    {
        if (Asistencia::where('IdSuscripcion', $this->IdAConfirmarAsistencia)->exists()) {
            $this->asistenciaYaMarcada = true;
            $this->confirmAsistencia = false;
            return;
        }

        Asistencia::create([
            'Fecha' => now(),
            'Asistencia' => true,
            'IdSuscripcion' => $this->IdAConfirmarAsistencia,
            'created_by' => Auth::id()
        ]);
        
   
        $this->mount($this->modoHistorial);

        session()->flash('success', 'Asistencia marcada correctamente.');
        $this->confirmAsistencia = false;
    }

    public function render()
    {
        return view('livewire.ConferenciaInscrita.conferencias-inscritas', [
            'conferencias' => $this->conferencias,
            'modoHistorial' => $this->modoHistorial,
        ]);
    }
}
