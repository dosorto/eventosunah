<?php

namespace App\Livewire\Localidad;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Localidad;

class Localidades extends Component
{
    use WithPagination;
    public $localidad, $localidad_id, $search;
    public $isOpen = 0;
    public function render()
    {
        $localidades = Localidad::where('localidad', 'like', '%'.$this->search.'%')->orderBy('id','ASC')->paginate(8);
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
    private function resetInputFields(){
        $this->localidad = '';
    }

    public function store()
    {
        $this->validate([
            'localidad' => 'required',
        ]);
   
        Localidad::updateOrCreate(['id' => $this->localidad_id], [
            'localidad' => $this->localidad,
        ]);
  
        session()->flash('message', 
            $this->localidad_id ? 'localidad Actualizada correctamente!' : 'localidad creada correctamente!');
  
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
     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        Localidad::find($id)->delete();
        session()->flash('message', 'Registro Eliminado correctamente!');
    }
}
