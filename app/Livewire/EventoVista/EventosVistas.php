<?php

namespace App\Livewire\EventoVista;

use App\Models\Modalidad;
use App\Models\Localidad;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Evento;

class EventosVistas extends Component
{
    use WithPagination;

    public $nombreevento, $descripcion, $organizador, $idmodalidad, $idlocalidad, $evento_id, $search;
    public $isOpen = 0;

    public function render()
    {
        $targetasEventos = Evento::with('modalidad', 'localidad')
            ->where('nombreevento', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'ASC')
            ->paginate(9);

        return view('livewire.EventoVista.eventos-vista', ['targetasEventos' => $targetasEventos]);
    }

    public $modalidades, $localidades;

    public function mount()
    {
        $this->modalidades = Modalidad::all();
        $this->localidades = Localidad::all();
    }
}
