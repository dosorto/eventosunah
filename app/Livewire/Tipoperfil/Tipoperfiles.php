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
            $tipoperfil = Tipoperfil::find($this->IdAEliminar);

            if (!$tipoperfil) {
                session()->flash('error', 'localidad no encontrada.');
                $this->confirmingDelete = false;
                return;
            }

            $tipoperfil->forceDelete();
            session()->flash('message', 'tipo perfil eliminado correctamente!');
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $tipoperfil = Tipoperfil::find($id);

        if (!$tipoperfil) {
            session()->flash('error', 'tipo perfil no encontrado.');
            return;
        }

        if ($tipoperfil->personas()->exists()) {
            session()->flash('error', 'No se puede eliminar el tipo de perfil: '. $tipoperfil->tipoperfil .', porque está enlazado a una o más personas');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $tipoperfil->tipoperfil;
        $this->confirmingDelete = true;
    }

}
