<?php

namespace App\Livewire\TipoPerfil;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Tipoperfil;

class Tipoperfiles extends Component
{
    use WithPagination;
    public $tipoperfil, $tipoperfil_id, $search;
    public $isOpen = 0;

    public function render()
    {
        // Usar el modelo Tipoperfil para las consultas
        $tipoperfiles = Tipoperfil::where('tipoperfil', 'like', '%'.$this->search.'%')->orderBy('id','DESC')->paginate(5);
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

    private function resetInputFields(){
        $this->tipoperfil = '';
    }

    public function store()
    {
        $this->validate([
            'tipoperfil' => 'required',
        ]);
   
        // Usar el modelo Tipoperfil para la creación/actualización
        Tipoperfil::updateOrCreate(['id' => $this->tipoperfil_id], [
            'tipoperfil' => $this->tipoperfil,
        ]);
  
        session()->flash('message', 
            $this->tipoperfil_id ? 'Tipo de perfil actualizado correctamente!' : 'Tipo de perfil creado correctamente!');
  
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        // Usar el modelo Tipoperfil para la búsqueda
        $tipoperfil = Tipoperfil::findOrFail($id);
        $this->tipoperfil_id = $id;
        $this->tipoperfil = $tipoperfil->tipoperfil;
    
        $this->openModal();
    }

    public function delete($id)
    {
        // Usar el modelo Tipoperfil para la eliminación
        Tipoperfil::find($id)->delete();
        session()->flash('message', 'Registro eliminado correctamente!');
    }
}
