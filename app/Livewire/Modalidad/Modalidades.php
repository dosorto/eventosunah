<?php

namespace App\Livewire\Modalidad;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Modalidad;

class Modalidades extends Component
{
    use WithPagination;
    public $modalidad, $modalidad_id, $search;
    public $isOpen = 0;
    public function render()
    {
        $modalidades = Modalidad::where('modalidad', 'like', '%'.$this->search.'%')->orderBy('id','ASC')->paginate(8);
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
    }

    public function store()
    {
        $this->validate([
            'modalidad' => 'required',
        ]);
   
        Modalidad::updateOrCreate(['id' => $this->modalidad_id], [
            'modalidad' => $this->modalidad,
        ]);
  
        session()->flash('message', 
            $this->modalidad_id ? 'Modalidad Actualizada correctamente!' : 'Modalidad creada correctamente!');
  
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
     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        Modalidad::find($id)->delete();
        session()->flash('message', 'Registro Eliminado correctamente!');
    }
}
