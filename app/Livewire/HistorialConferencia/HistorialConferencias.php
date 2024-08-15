<?php

namespace App\Livewire\HistorialConferencia;

use Livewire\Component;
use App\Models\Asistencia;
use App\Models\Conferencia;
use Illuminate\Support\Facades\Auth;

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

        
        $suscripciones = Asistencia::whereHas('suscripcion', function($query) use ($personaId) {
            $query->where('IdPersona', $personaId);
        })->with('suscripcion.conferencia') 
          ->get()
          ->map(function ($asistencia) {
              return $asistencia->suscripcion->conferencia;
          })->unique('id');
        
        $this->conferencias = $suscripciones;
    }

    public function render()
    {
        return view('livewire.HistorialConferencia.historial-conferencias', [
            'conferencias' => $this->conferencias,
        ]);
    }
    
}
