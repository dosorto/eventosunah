<?php

namespace App\Livewire\Evento;

use App\Models\Modalidad;
use App\Models\Localidad;
use App\Models\Conferencia;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Evento;

class Eventos extends Component
<<<<<<< HEAD
=======

>>>>>>> origin/jose
{
    use WithPagination;

    public $nombreevento, $descripcion, $organizador, $fechainicio, $fechafinal, $horainicio, $horafin, $idmodalidad, $idlocalidad, $idconferencia, $evento_id, $search;
    public $isOpen = 0;

    public function render()
    {
        $nombreeventos = Evento::with('modalidad', 'localidad', 'conferencia')
            ->where('nombreevento', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'ASC')
            ->paginate(8);

        return view('livewire.Evento.eventos', ['nombreeventos' => $nombreeventos]);
    }

    public $modalidades, $localidades, $conferencias;

    public function mount()
    {
        $this->modalidades = Modalidad::all();
        $this->localidades = Localidad::all();
        $this->conferencias = Conferencia::all();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
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
        $this->nombreevento = '';
        $this->descripcion = '';
        $this->organizador = '';
        $this->fechainicio = '';
        $this->fechafinal = '';
        $this->horainicio = '';
        $this->horafin = '';
        $this->idmodalidad = '';
        $this->idlocalidad = '';
        $this->idconferencia = '';
    }

    public function store()
    {
        $this->validate([
            'nombreevento' => 'required',
            'descripcion' => 'required',
            'organizador' => 'required',
            'fechainicio' => 'required',
            'fechafinal' => 'required',
            'horainicio' => 'required',
            'horafin' => 'required',
            'idmodalidad' => 'required',
            'idlocalidad' => 'required',
            'idconferencia' => 'required',
        ]);

        Evento::updateOrCreate(['id' => $this->evento_id], [
            'nombreevento' => $this->nombreevento,
            'descripcion' => $this->descripcion,
            'organizador' => $this->organizador,
            'fechainicio' => $this->fechainicio,
            'fechafinal' => $this->fechafinal,
            'horainicio' => $this->horainicio,
            'horafin' => $this->horafin,
            'idmodalidad' => $this->idmodalidad,
            'idlocalidad' => $this->idlocalidad,
            'idconferencia' => $this->idconferencia,
        ]);

        session()->flash('message', 
            $this->evento_id ? 'Evento Actualizado correctamente!' : 'Evento creado correctamente!');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $nombreevento = Evento::findOrFail($id);
        $this->evento_id = $id;
        $this->nombreevento = $nombreevento->nombreevento;
        $this->descripcion = $nombreevento->descripcion;
        $this->organizador = $nombreevento->organizador;
        $this->fechainicio = $nombreevento->fechainicio;
        $this->fechafinal = $nombreevento->fechafinal;
        $this->horainicio = $nombreevento->horainicio;
        $this->horafin = $nombreevento->horafin;
        $this->idmodalidad = $nombreevento->idmodalidad;
        $this->idlocalidad = $nombreevento->idlocalidad;
        $this->idconferencia = $nombreevento->idconferencia;

        $this->openModal();
    }

    public function delete($id)
    {
<<<<<<< HEAD
        Evento::find($id)->delete();
        session()->flash('message', 'Registro Eliminado correctamente!');
    }
}
=======
        
        Evento::find($id)->delete();
        session()->flash('message', 'Registro Eliminado correctamente!');
    }

}
>>>>>>> origin/jose
