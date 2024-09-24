<?php

namespace App\Livewire\Pago;

use App\Models\Conferencia;
use App\Models\Pago;
use Livewire\Component;

class Pagos extends Component
{
    public $conferencia;
    public $persona;
    public function mount($conferencia)
    {
        $conferenciaId = Conferencia::find($conferencia);
        $this->conferencia = $conferenciaId;

        $this->persona = auth()->user()->persona;
    }

    public function realizarPago(){
        
        $user = Pago::create([
            'name' => $this->name,
            'email' => $this->email,
        ]);

    }

    public function render()
    {
        return view('livewire.Pagos.pagos', [
            'conferencia' => $this->conferencia,
            'persona' => $this->persona
        ]); 
    }
}
