<?php

namespace App\Livewire\VistaConferencia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Conferencia;
use App\Models\Conferencista;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class VistaConferencias extends Component
{
    use WithPagination;

    public $evento;
    public $conferencias;


    public function mount(Evento $evento)
    {
        $this->evento = $evento;
        $this->conferencias =  $evento->conferencias;
    }

    public function render()
    {
        Auth::user()->persona->suscripciones;
        return view('livewire.VistaConferencia.vista-conferencia');
    }

    public function inscribirse(Conferencia $conferencia)
    {
       Auth::user()->persona->suscripciones()->updateOrCreate([
            'IdConferencia' => $conferencia->id,
            'created_by' => Auth::id()
        ]);

    }
}
