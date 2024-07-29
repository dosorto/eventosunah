<?php

namespace App\Livewire\Departamento;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Departamento;
use App\Models\Carrera; 

class Departamentos extends Component
{
    use WithPagination;
    
    public $departamento, $departamento_id, $search;
    public $isOpen = 0;

    public function render()
    {
        $departamentos = Departamento::where('departamento', 'like', '%'.$this->search.'%')
                        ->orderBy('id','DESC')
                        ->paginate(8);

        return view('livewire.Departamento.departamentos', ['departamentos' => $departamentos]);
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
        $this->departamento = '';
    }

    public function store()
{
    $this->validate([
        'departamento' => [
            'required',
            'string',
            'max:255',
            'unique:departamentos,departamento,' . $this->departamento_id, 
        ],
    ]);

    Departamento::updateOrCreate(
        ['id' => $this->departamento_id],
        [
            'departamento' => $this->departamento,
        ]
    );

    session()->flash('message', 
        $this->departamento_id ? 'Departamento actualizado correctamente!' : 'Departamento creado correctamente!'
    );

    $this->closeModal();
    $this->resetInputFields();
}

    public function edit($id)
    {
        $departamento = Departamento::findOrFail($id);
        $this->departamento_id = $id;
        $this->departamento = $departamento->departamento;
    
        $this->openModal();
    }

    public function delete($id)
    {
        
        $carrerasAsociadas = Carrera::where('IdDepartamento', $id)->exists();

        if ($carrerasAsociadas) {
            session()->flash('message', 'No se puede eliminar el departamento porque está asociado a una o más carreras.');
        } else {
            Departamento::find($id)->delete();
            session()->flash('message', 'Departamento eliminado correctamente!');
        }
    }
}

