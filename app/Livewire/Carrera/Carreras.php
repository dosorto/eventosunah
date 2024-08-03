<?php

namespace App\Livewire\Carrera;

use App\Models\Departamento;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Carrera;

class Carreras extends Component
{
    use WithPagination;
    public $carrera, $IdDepartamento, $carrera_id, $search;
    public $isOpen = 0;
    public $confirmingDeletion = false; 
    public $deleteId;

    public function render()
    {
        $carreras = Carrera::with('departamento')
            ->where('carrera', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);

        return view('livewire.Carrera.carreras', ['carreras' => $carreras]);
    }

    public $departamentos;

    public function mount()
    {
        $this->departamentos = Departamento::all();
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
        $this->carrera = '';
        $this->IdDepartamento = '';
    }

    public function store()
    {
        $this->validate([
            'carrera' => [
                'required',
                'string',
                'max:255',
                'unique:carreras,carrera,' . $this->carrera_id, 
            ],
            'IdDepartamento' => 'required|exists:departamentos,id', 
        ]);

        Carrera::updateOrCreate(
            ['id' => $this->carrera_id],
            [
                'carrera' => $this->carrera,
                'IdDepartamento' => $this->IdDepartamento,
            ]
        );

        session()->flash(
            'message',
            $this->carrera_id ? 'Carrera actualizada correctamente!' : 'Carrera creada correctamente!'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $carrera = Carrera::findOrFail($id);
        $this->carrera_id = $id;
        $this->carrera = $carrera->carrera;
        $this->IdDepartamento = $carrera->IdDepartamento;

        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDeletion = true;
    }
    
    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
    }
    
    public function delete()
    {
        Carrera::find($this->deleteId)->delete();
        session()->flash('message', 'Registro eliminado correctamente!');
        $this->confirmingDeletion = false;
    }
}
