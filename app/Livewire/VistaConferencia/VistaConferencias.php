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

    public $nombre, $descripcion, $fecha, $horaInicio, $horaFin, $lugar, $linkreunion, $idConferencista, $conferencia_id, $search, $IdEvento;
    public $isOpen = 0;
    public $inputSearchConferencista = '';
    public $searchConferencistas = [];
    public $inputSearchEvento = '';
    public $searchEventos = [];

    public function render()
    {
        $conferencias = Conferencia::with('conferencista', 'evento')
            ->where('nombre', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'ASC')
            ->paginate(8);

        $eventos = Evento::all();

        return view('livewire.VistaConferencia.vista-conferencia', [
            'conferencias' => $conferencias,
            'eventos' => $eventos,
            'searchConferencistas' => $this->searchConferencistas
        ]);
    }

    public function inscribirse(Conferencia $conferencia)
    {
       Auth::user()->persona->suscripciones()->updateOrCreate([
            'IdConferencia' => $conferencia->id,
            'created_by' => Auth::id()
        ]);

        session()->flash('success', 'Te has inscrito a la conferencia.');
    }
}
