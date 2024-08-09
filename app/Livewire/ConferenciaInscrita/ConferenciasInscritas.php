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
            $this->conferencias = collect(); 
        }
    }

    public function desuscribirse($id)
    {
        $this->IdAConfirmarCancelacion = $id;
        $this->confirmCancelacion = true;

        // Buscar la suscripción con el ID proporcionado
        $suscripcion = Suscripcion::where('IdConferencia', $id)->first();
        
        if (!$suscripcion) {
            session()->flash('error', 'No se pudo encontrar la inscripción.');
            return;
        }
        
        // Verificar si la suscripción tiene asistencias registradas
        if ($suscripcion->asistencias()->exists()) {
            $this->errorCancelacion = true;
            $this->confirmCancelacion = false; 
            return;
        }
        
        // Preparar datos para la confirmación de la cancelación
        $this->nombreAConfirmarCancelacion = $suscripcion->conferencia->nombre;
        $this->IdAEliminar = $suscripcion->id;
        $this->nombreAEliminar = $suscripcion->conferencia->nombre; // Usa el nombre de la conferencia
        $this->confirmingDelete = true;
    }

    public function confirmarCancelacion()
    {
        $suscripcion = Suscripcion::where('IdConferencia', $this->IdAConfirmarCancelacion)->first();

        if (!$suscripcion) {
            session()->flash('error', 'No se pudo encontrar la inscripción.');
            $this->confirmCancelacion = false;
            return;
        }

        // Verificar si la suscripción tiene asistencias registradas
        if ($suscripcion->asistencias()->exists()) {
            session()->flash('error', 'No se puede eliminar la inscripción porque está enlazada a una o más asistencias.');
            $this->confirmCancelacion = false;
            return;
        }

        // Proceder con la eliminación
        $suscripcion->delete();
        session()->flash('success', 'Eliminaste la inscripción a la conferencia.');
        $this->confirmCancelacion = false;

        // Actualizar la lista de conferencias inscritas
        $this->mount();
    }

    public function asistencia($id)
    {
        $this->IdAConfirmarAsistencia = $id;

        // Verificar si ya se ha marcado la asistencia
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
            // Mostrar el modal de asistencia ya marcada
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
        $this->asistenciaMarcada[$this->IdAConfirmarAsistencia] = true;

        session()->flash('success', 'Asistencia marcada correctamente.');
        $this->confirmAsistencia = false;
    }

    public function render()
    {
        return view('livewire.ConferenciaInscrita.conferencias-inscritas', [
            'conferencias' => $this->conferencias,
        ]);
    }
}
