<?php

namespace App\Livewire\EventoVista;

use App\Models\Localidad;
use App\Models\Evento;
use App\Models\Modalidad;
use Livewire\Component;
use Livewire\WithPagination;

class EventosVistas extends Component
{
    use WithPagination;

    public $nombreevento, $descripcion, $organizador, $idmodalidad, $idlocalidad, $evento_id, $search;
    public $isOpen = 0;

    public function render()
    {
        $Eventos = Evento::with('modalidad', 'localidad')
            ->where('nombreevento', 'like', '%' . $this->search . '%')
            ->published()
            ->latest('published_at')
            ->paginate(9);

        return view('livewire.EventoVista.eventos-vista', ['Eventos' => $Eventos]);
    }

    public $modalidades, $localidades;

    public function mount()
    {
        $this->modalidades = Modalidad::all();
        $this->localidades = Localidad::all();
    }
}
