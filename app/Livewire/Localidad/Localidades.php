<?php

namespace App\Livewire\Localidad;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Localidad;
use App\Models\Evento;
use App\Models\Modalidad;

class Localidades extends Component
{
    use WithPagination;

    public $localidad, $localidad_id, $search;
    public $isOpen = 0;
    public $showDeleteModal = false;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;

    public function render()
    {
        $localidades = Localidad::where('localidad', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->paginate(8);
        return view('livewire.Localidad.localidades', ['localidades' => $localidades]);
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
        $this->localidad = '';
    }

    public function store()
    {
        $this->validate([
            'localidad' => 'required|string|max:255|unique:localidads,localidad,' . $this->localidad_id,
        ]);

        Localidad::updateOrCreate(['id' => $this->localidad_id], ['localidad' => $this->localidad]);

        session()->flash('message', 
            $this->localidad_id ? 'Localidad actualizada correctamente!' : 'Localidad creada correctamente!'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $localidad = Localidad::findOrFail($id);
        $this->localidad_id = $id;
        $this->localidad = $localidad->localidad;

        $this->openModal();
    }
     
    public function delete()
    {
        if ($this->confirmingDelete) {
            $localidad = Localidad::find($this->IdAEliminar);

            if (!$localidad) {
                session()->flash('error', 'localidad no encontrada.');
                $this->confirmingDelete = false;
                return;
            }

            $localidad->forceDelete();
            session()->flash('message', 'localidad eliminada correctamente!');
            $this->confirmingDelete = false;
        }

        Localidad::destroy($this->IdAEliminar);
        session()->flash('message', 'Localidad eliminada correctamente!');
        $this->confirmingDelete = false;
    }

    public function confirmDelete($id)
    {
        $localidad = Localidad::find($id);

        if (!$localidad) {
            session()->flash('error', 'localidad no encontrada.');
            return;
        }

        if ($localidad->eventos()->exists()) {
            session()->flash('error', 'No se puede eliminar la localidad: '. $localidad->localidad .', porque está enlazado a uno o más eventos');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $localidad->localidad;
        $this->confirmingDelete = true;
    }

}
