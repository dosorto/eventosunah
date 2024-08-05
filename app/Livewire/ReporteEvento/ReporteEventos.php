<?php

namespace App\Livewire\ReporteEvento;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Conferencia;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class ReporteEventos extends Component
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
        return view('livewire.ReporteEvento.reporte-eventos');
    }
}