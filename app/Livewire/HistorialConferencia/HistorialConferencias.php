<?php

namespace App\Livewire\HistorialConferencia;

use Livewire\Component;
use App\Models\Asistencia;
use Illuminate\Support\Facades\Auth;

class HistorialConferencias extends Component
{
    public $conferencias = [];
    public $search = '';

    public function mount()
    {
        $this->loadConferencias();
    }

    public function loadConferencias()
    {
        $personaId = Auth::user()->persona->id;

        
        $suscripciones = Asistencia::where('asistencia', 1) 
            ->whereHas('suscripcion', function($query) use ($personaId) {
                $query->where('IdPersona', $personaId);
            })
            ->with('suscripcion.conferencia.evento', 'suscripcion.conferencia.conferencista.persona')
            ->get()
            ->map(function ($asistencia) {
                return [
                    'asistencia_id' => $asistencia->id,
                    'conferencia' => $asistencia->suscripcion->conferencia
                ];
            })
            ->unique('conferencia.id');

        $this->conferencias = $suscripciones;
    }

    public function render()
    {
        
        $conferencias = collect($this->conferencias)->filter(function ($item) {
            return str_contains(strtolower($item['conferencia']->nombre), strtolower($this->search)) ||
                   str_contains(strtolower($item['conferencia']->evento->nombreevento), strtolower($this->search)) ||
                   str_contains(strtolower($item['conferencia']->conferencista->persona->nombre), strtolower($this->search)) ||
                   str_contains(strtolower($item['conferencia']->conferencista->persona->apellido), strtolower($this->search));
        });
        return view('livewire.HistorialConferencia.historial-conferencias', [
            'conferencias' => $this->conferencias,
        ]);
    }
}