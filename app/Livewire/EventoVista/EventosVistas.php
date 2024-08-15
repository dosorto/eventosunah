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
        $Eventos = Evento::with('modalidad', 'localidad')
            ->where('nombreevento', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.EventoVista.eventos-vista', ['Eventos' => $Eventos]);
    }

    public $modalidades, $localidades;

    public function mount()
    {
        $this->modalidades = Modalidad::all();
        $this->localidades = Localidad::all();
    }
}
