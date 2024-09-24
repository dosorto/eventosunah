<?php

namespace App\Livewire\Pago;

use App\Models\Evento;
use App\Models\Recibopago;
use Livewire\Component;
use App\Models\Inscripcion;
class ReciboPagos extends Component
{
    public $evento;
    public $persona;
    public $fecha;
    public $foto;

    public function mount($evento)
    {
        $this->evento = Conferencia::find($evento);
        $this->persona = auth()->user()->persona;
        $this->fecha = now()->format('Y-m-d'); 
    }

    public function realizarPago()
    {
        $this->validate([
            'foto' => 'required|image|max:2048', 
        ]);

        $path = $this->foto->store('fotos', 'public');

        Recibopago::create([
            'idEvento' => $this->evento->id,
            'idPersona' => $this->persona->id,
            'fecha' => $this->fecha,
            'foto' => $path,
        ]);
        Inscripcion::create([
            'IdEvento' => $this->evento->id,
            'IdPersona' => $this->persona->id,
            'IdRecibo' => $recibo->id, 
            'Status' => 'pendiente',
        ]);
        
    }

    public function render()
    {
        return view('livewire.ReciboPagos.recibopagos', [
            'eventos' => $this->evento,
            'persona' => $this->persona,
        ]);
    }
}
