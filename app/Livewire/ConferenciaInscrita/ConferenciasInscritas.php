<?php

namespace App\Livewire\ConferenciaInscrita;

use Livewire\Component;
use App\Models\Suscripcion;
use Illuminate\Support\Facades\Auth;
use App\Models\Persona;

class ConferenciasInscritas extends Component
{
    public $conferencias;

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
            session()->flash('success', 'Te has desuscrito exitosamente.');
        } else {
            session()->flash('error', 'No se pudo desuscribir.');
        }
        return redirect()->route('conferencias-inscritas'); // Reemplaza 'nombre_de_tu_ruta' con la ruta correcta
    }

    public function render()
    {
        return view('livewire.ConferenciaInscrita.conferencias-inscritas', [
            'conferencias' => $this->conferencias,
        ]);
    }
}