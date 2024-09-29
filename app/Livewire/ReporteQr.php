<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Evento;
class ReporteQr extends Component
{
   
    public $evento;
    public $conferencias;

    public function mount(Evento $evento)
    {
        $this->evento = $evento;
        $this->conferencias =  $evento->conferencias;
        
    }
    public function render()
    {
        return view('livewire.reporte-qr');
    }
}
