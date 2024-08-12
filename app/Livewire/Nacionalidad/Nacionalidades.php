<?php

namespace App\Livewire\Nacionalidad;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Nacionalidad;
use App\Models\Persona;
class Nacionalidades extends Component
{
    use WithPagination;
    public $nombreNacionalidad, $nacionalidad_id, $search;
    public $isOpen = 0;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;
    public function render()
    {
        $nacionalidades = Nacionalidad::where('nombreNacionalidad', 'like', '%'.$this->search.'%')->orderBy('nombreNacionalidad','ASC')->paginate(5);
        return view('livewire.Nacionalidad.nacionalidades', ['nacionalidades' => $nacionalidades]);
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
    private function resetInputFields(){
        $this->nombreNacionalidad = '';
    }

    public function store()
{
    $this->validate([
        'nombreNacionalidad' => [
            'required',
            'string',
            'max:255',
            'unique:nacionalidads,nombreNacionalidad,' . $this->nacionalidad_id,
        ],
    ]);

    Nacionalidad::updateOrCreate(['id' => $this->nacionalidad_id], [
        'nombreNacionalidad' => $this->nombreNacionalidad,
    ]);

    session()->flash('message', 
        $this->nacionalidad_id ? 'Nacionalidad actualizada correctamente!' : 'Nacionalidad creada correctamente!'
    );

    $this->closeModal();
    $this->resetInputFields();
}

    public function edit($id)
    {
        $nacionalidad = Nacionalidad::findOrFail($id);
        $this->nacionalidad_id = $id;
        $this->nombreNacionalidad = $nacionalidad->nombreNacionalidad;
    
        $this->openModal();
    }
     
    public function delete()
    {
        if ($this->confirmingDelete) {
            $nacionalidad = Nacionalidad::find($this->IdAEliminar);

            if (!$nacionalidad) {
                session()->flash('error', 'nacionalidad no encontrada.');
                $this->confirmingDelete = false;
                return;
            }

            $nacionalidad->forceDelete();
            session()->flash('message', 'nacionalidad eliminada correctamente!');
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $nacionalidad = Nacionalidad::find($id);

        if (!$nacionalidad) {
            session()->flash('error', 'Nacionalidad no encontrada.');
            return;
        }

        if ($nacionalidad->personas()->exists()) {
            session()->flash('error', 'No se puede eliminar la nacionalidad: '. $nacionalidad->nombreNacionalidad . ', porque está enlazado a una  o más personas:');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $nacionalidad->nombreNacionalidad;
        $this->confirmingDelete = true;
    }

}
