<?php

namespace App\Livewire\TipoPerfil;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Tipoperfil;
use App\Models\Persona;

class Tipoperfiles extends Component
{
    use WithPagination;

    public $tipoperfil, $tipoperfil_id, $search;
    public $isOpen = 0;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;
    public $errorMessage;

    public function render()
    {
        $tipoperfiles = Tipoperfil::where('tipoperfil', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'DESC')
            ->paginate(5);

        return view('livewire.Tipoperfil.tipoperfiles', ['tipoperfiles' => $tipoperfiles]);
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
        $this->tipoperfil = '';
        $this->tipoperfil_id = null;
    }

    public function store()
    {
        $this->validate([
            'tipoperfil' => ['required', 'unique:tipoperfils,tipoperfil,' . $this->tipoperfil_id],
        ]);

        Tipoperfil::updateOrCreate(['id' => $this->tipoperfil_id], [
            'tipoperfil' => $this->tipoperfil,
        ]);

        session()->flash('message', 
            $this->tipoperfil_id ? 'Tipo de perfil actualizado correctamente!' : 'Tipo de perfil creado correctamente!'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $tipoperfil = Tipoperfil::findOrFail($id);
        $this->tipoperfil_id = $id;
        $this->tipoperfil = $tipoperfil->tipoperfil;

        $this->openModal();
    }

    public function delete()
    {
        if ($this->confirmingDelete) {
            $tipoperfil = Tipoperfil::withTrashed()->find($this->IdAEliminar);

            if (!$tipoperfil) {
                session()->flash('message', 'Tipo de perfil no encontrado.');
                return;
            }

            $tipoperfil->forceDelete();
            session()->flash('message', 'Tipo de perfil eliminado permanentemente!');
            $this->confirmingDelete = false; 
        }
    }

    public function confirmDelete($id)
    {
        $tipoperfil = Tipoperfil::find($id);
        $personasAsociadas = Persona::where('IdTipoperfil', $id)->exists();

        if ($personasAsociadas) {
            $this->errorMessage = 'No se puede eliminar este tipo de perfil porque está asociado a una o más personas.';
            $this->confirmingDelete = true;
        } else {
            $this->IdAEliminar = $id;
            $this->nombreAEliminar = $tipoperfil->tipoperfil; 
            $this->errorMessage = '';
            $this->confirmingDelete = true;
        }
    }
}
