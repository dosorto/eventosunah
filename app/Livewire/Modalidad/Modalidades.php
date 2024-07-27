<?php

namespace App\Livewire\Modalidad;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Modalidad;
use App\Models\Evento;

class Modalidades extends Component
{
    use WithPagination;

    public $modalidad, $modalidad_id, $search;
    public $isOpen = 0;

    public function render()
    {
        $modalidades = Modalidad::where('modalidad', 'like', '%'.$this->search.'%')->orderBy('id','DESC')->paginate(8);
        return view('livewire.Modalidad.modalidades', ['modalidades' => $modalidades]);
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
        $this->modalidad = '';
        $this->modalidad_id = null;
    }

    public function store()
    {
        $this->validate([
            'modalidad' => [
                'required',
                'unique:modalidads,modalidad,' . $this->modalidad_id,
            ],
        ]);

        Modalidad::updateOrCreate(['id' => $this->modalidad_id], [
            'modalidad' => $this->modalidad,
        ]);

        session()->flash('message', 
            $this->modalidad_id ? 'Modalidad actualizada correctamente!' : 'Modalidad creada correctamente!'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        $this->modalidad_id = $id;
        $this->modalidad = $modalidad->modalidad;

        $this->openModal();
    }

    public function delete($id)
    {
        $eventosAsociados = Evento::where('IdModalidad', $id)->exists();

        if ($eventosAsociados) {
            session()->flash('message', 'No se puede eliminar la modalidad porque está asociada a uno o más eventos.');
        } else {
            Modalidad::find($id)->delete();
            session()->flash('message', 'Modalidad eliminada correctamente!');
        }
    }
}
