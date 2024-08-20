<?php

namespace App\Livewire\EventoVista;

use App\Models\Modalidad;
use App\Models\Localidad;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Evento;
use Carbon\Carbon;

class EventosVistas extends Component
{
    use WithPagination;

    public $nombreevento, $descripcion, $organizador, $idmodalidad, $idlocalidad, $evento_id, $search;
    public $isOpen = 0;

    public function render()
    {
        $Eventos = Evento::with('modalidad', 'localidad')
            ->where('nombreevento', 'like', '%' . $this->search . '%')
            ->where('fechaInicio', '<=', Carbon::today())
            ->where('fechaFinal', '>=', Carbon::today())
            ->orderBy('id', 'DESC')
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

