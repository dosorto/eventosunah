<?php

namespace App\Livewire\Conferencia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Conferencia;
use App\Models\Conferencista;
use App\Models\Evento;

class Conferencias extends Component
{
    use WithPagination;

    public $nombre, $descripcion, $fecha, $horaInicio, $horaFin, $lugar, $linkreunion, $idConferencista, $conferencia_id, $search, $IdEvento;
    public $isOpen = 0;
    public $inputSearchConferencista = '';
    public $searchConferencistas = [];
    public $inputSearchEvento = '';
    public $searchEventos = [];
    public $showDetails = false;
    public $selectedConferencia;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;
    public function viewDetails($id)
    {
        $this->selectedConferencia = Conferencia::find($id);
        $this->showDetails = true;
    }

    public function closeDetails()
    {
        $this->showDetails = false;
    }

    public function render()
    {
        $conferencias = Conferencia::with('conferencista', 'evento')
            ->where('IdEvento', $this->IdEvento)
            ->where('nombre', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'DESC')
            ->paginate(8);

        $eventos = Evento::all();

        return view('livewire.Conferencia.conferencias', [
            'conferencias' => $conferencias,
            'eventos' => $eventos,
            'searchConferencistas' => $this->searchConferencistas
        ]);
    }
    

    public function updatedInputSearchEvento()
    {
        $query = Evento::query();

        // Busca por nombre del evento si se proporciona
        if (!empty($this->inputSearchEvento)) {
            $query->where('nombreevento', 'like', '%' . $this->inputSearchEvento . '%');
        }

        // Busca por IdEvento si se proporciona
        if (!empty($this->IdEvento)) {
            $query->where('id', $this->IdEvento);
        }

        $this->searchEventos = $query->get();
    }

    public function selectEvento($eventoId)
    {
        $this->IdEvento = $eventoId;
        $evento = Evento::find($eventoId);
        $this->inputSearchEvento = $evento->nombreevento;
        $this->searchEventos = [];
    }

    public function updatedInputSearchConferencista()
    {
        $this->searchConferencistas = Conferencista::whereHas('persona', function ($query) {
            $query->where('nombre', 'like', '%' . $this->inputSearchConferencista . '%')
                  ->orWhere('apellido', 'like', '%' . $this->inputSearchConferencista . '%');
        })->get();
    }

    public function selectConferencista($conferencistaId)
    {
        $this->idConferencista = $conferencistaId;
        $conferencista = Conferencista::find($conferencistaId);
        if ($conferencista) {
            $this->inputSearchConferencista = $conferencista->persona->nombre . ' ' . $conferencista->persona->apellido;
        }
        $this->searchConferencistas = [];
    }
   
    

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->render();
    }

    public function agregarConferencia($eventoId)
    {
        $this->IdEvento = $eventoId;
        $this->create();
        $this->render();
    }
    

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->fecha = '';
        $this->horaInicio = '';
        $this->horaFin = '';
        $this->lugar = '';
        $this->linkreunion = '';
        $this->idConferencista = '';
        $this->inputSearchConferencista = '';
        $this->searchConferencistas = [];
        $this->IdEvento = '';
    }
    
    public function store()
    {
        $this->validate([
            'IdEvento' => 'required',
            'nombre' => 'required',
            'descripcion' => 'required',
            'fecha' => 'required',
            'horaInicio' => 'required',
            'horaFin' => 'required',
            'lugar' => 'required',
            'linkreunion' => 'required',
            'idConferencista' => 'required'
        ]);

        // Crear o actualizar la conferencia
        Conferencia::updateOrCreate(['id' => $this->conferencia_id], [
            'IdEvento' => $this->IdEvento,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'fecha' => $this->fecha,
            'horaInicio' => $this->horaInicio,
            'horaFin' => $this->horaFin,
            'lugar' => $this->lugar,
            'linkreunion' => $this->linkreunion,
            'idConferencista' => $this->idConferencista,
        ]);
        session()->flash('message', $this->conferencia_id ? 'Conferencia actualizada correctamente!' : 'Conferencia creada correctamente!');
        $this->render();
        
        $this->closeModal();
        $this->resetInputFields();
        $this->render(); 
    }


    public function edit($id)
    {
        $conferencia = Conferencia::findOrFail($id);
        $this->conferencia_id = $id;
        $this->IdEvento = $conferencia->IdEvento;
        $this->nombre = $conferencia->nombre;
        $this->descripcion = $conferencia->descripcion;
        $this->fecha = $conferencia->fecha;
        $this->horaInicio = $conferencia->horaInicio;
        $this->horaFin = $conferencia->horaFin;
        $this->lugar = $conferencia->lugar;
        $this->linkreunion = $conferencia->linkreunion;
        $this->idConferencista = $conferencia->idConferencista;

        $conferencista = Conferencista::find($this->idConferencista);
        if ($conferencista) {
            $this->inputSearchConferencista = $conferencista->persona->nombre . ' ' . $conferencista->persona->apellido;
        }

        $this->openModal();
        $this->render();
    }
    public function delete()
    {
        if ($this->confirmingDelete) {
            $conferencia = Conferencia::find($this->IdAEliminar);

            if (!$conferencia) {
                session()->flash('error', 'Conferencia no encontrada.');
                $this->confirmingDelete = false;
                return;
            }

            $conferencia->forceDelete();
            session()->flash('message', 'Conferencia eliminada correctamente!');
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $conferencia = Conferencia::find($id);

        if (!$conferencia) {
            session()->flash('error', 'Conferencia no encontrada.');
            return;
        }

        if ($conferencia->suscripciones()->exists()) {
            session()->flash('error', 'No se puede eliminar la conferencia:'.$conferencia->nombre .'porque está enlazada a una o más suscripciones.');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $conferencia->nombre;
        $this->confirmingDelete = true;
    }

    public function mount(Evento $evento)
    {
        if ($evento->id) {
            $this->openModal();
            $this->IdEvento = $evento->id;
            $this->inputSearchEvento = $evento->nombreevento; 
              
        }

    }

}