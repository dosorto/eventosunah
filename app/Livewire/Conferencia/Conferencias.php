<?php

namespace App\Livewire\Conferencia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Conferencia;
use App\Models\Conferencista;

class Conferencias extends Component
{
    use WithPagination;

    public $nombre, $descripcion, $fecha, $horaInicio, $horaFin, $lugar, $linkreunion, $idConferencista, $conferencia_id, $search;
    public $isOpen = 0;
    public $conferencistas; // Declarar la propiedad $conferencistas

    public function render()
    {
        $conferencias = Conferencia::with('conferencista')
                        ->where('nombre', 'like', '%'.$this->search.'%')
                        ->orderBy('id', 'ASC')
                        ->paginate(8);

        return view('livewire.Conferencia.conferencias', [
            'conferencias' => $conferencias,
            'conferencistas' => Conferencista::all(), // Obtener y pasar los conferencistas a la vista
        ]);
    }

    public function mount()
    {
        $this->conferencistas = Conferencista::all();  
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
        $this->nombre = '';
        $this->descripcion = '';
        $this->fecha = '';
        $this->horaInicio = '';
        $this->horaFin = '';
        $this->lugar = '';
        $this->linkreunion = '';
        $this->idConferencista = '';
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'fecha' => 'required',
            'horaInicio' => 'required',
            'horaFin' => 'required',
            'lugar' => 'required',
            'linkreunion' => 'required',
            'idConferencista' => 'required', // Asegúrate de que este campo esté incluido en la validación
        ]);

        Conferencia::updateOrCreate(['id' => $this->conferencia_id], [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'fecha' => $this->fecha,
            'horaInicio' => $this->horaInicio,
            'horaFin' => $this->horaFin,
            'lugar' => $this->lugar,
            'linkreunion' => $this->linkreunion,
            'idConferencista' => $this->idConferencista, 
        ]);
        

        session()->flash('message', $this->conferencia_id ? 'Conferencia Actualizada correctamente!' : 'Conferencia creada correctamente!');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $conferencia = Conferencia::findOrFail($id);
        $this->conferencia_id = $id;
        $this->nombre = $conferencia->nombre;
        $this->descripcion = $conferencia->descripcion;
        $this->fecha = $conferencia->fecha;
        $this->horaInicio = $conferencia->horaInicio;
        $this->horaFin = $conferencia->horaFin;
        $this->lugar = $conferencia->lugar;
        $this->linkreunion = $conferencia->linkreunion;
        $this->idConferencista = $conferencia->conferencista_id;

        $this->openModal();
    }

    public function delete($id)
    {
        Conferencia::find($id)->delete();
        session()->flash('message', 'Registro Eliminado correctamente!');
    }
}
