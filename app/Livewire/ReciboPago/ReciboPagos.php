<?php

namespace App\Livewire\ReciboPago;

use App\Models\Evento;
use App\Models\Recibopago;
use App\Models\Inscripcion;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReciboPagos extends Component
{
    use WithFileUploads;

    public $evento;
    public $persona;
    public $fecha;
    public $foto;

    public function mount(Evento $evento)
    {
        $this->evento = $evento;
        $this->persona = auth()->user()->persona;
        $this->fecha = now()->format('Y-m-d');
    }

    public function realizarPago()
    {

        $this->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,jfif',
        ]);

        // Guardar la foto
        $path = $this->foto->store('recibo', 'public');

        // Crear el recibo
        $recibo = Recibopago::updateOrCreate(
            [
                'idPersona' => $this->persona->id,
                'idEvento' => $this->evento->id,
            ],
            [
                'fecha' => $this->fecha,
                'foto' => $path,
            ]
        );

        // Crear la inscripción
        Inscripcion::updateOrCreate(
            [
                'IdEvento' => $this->evento->id,
                'IdPersona' => $this->persona->id,
            ],
            [
                'IdRecibo' => $recibo->id,
                'Status' => 'Pendiente',

            ]
        );

        // Mensaje de éxito
        session()->flash('message', 'El comprobante se ha subido con éxito.');

        // Opcional: Resetear campos
        $this->reset(['foto']);
        return redirect()->route('eventoVista');
    }

    public function render()
    {
        return view('livewire.ReciboPagos.recibopagos', [
            'evento' => $this->evento,
            'persona' => $this->persona,
        ]);
    }
}