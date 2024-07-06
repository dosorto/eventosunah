<?php

namespace App\Livewire\Carrera;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Carrera;

class Carreras extends Component
{
    use WithPagination;
    public $carrera, $departamento_id, $search;
    public $isOpen = 0;
        public function render()
{
    $carreras = Carrera::with('departamento')
                    ->where('carrera', 'like', '%'.$this->search.'%')
                    ->orderBy('id', 'ASC')
                    ->paginate(8);
                    
    return view('livewire.Carrera.carreras', ['carreras' => $carreras]);
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
        $this->carrera = '';
    }

    public function store()
    {
        $this->validate([
            'carrera' => 'required',
        ]);
   
        Carrera::updateOrCreate(['id' => $this->departamento_id], [
            'carrera' => $this->carrera,
        ]);
  
        session()->flash('message', 
            $this->departamento_id ? 'Carrera Actualizada correctamente!' : 'Carrera creada correctamente!');
  
        $this->closeModal();
        $this->resetInputFields();
    }
    public function edit($id)
    {
        $carrera = Carrera::findOrFail($id);
        $this->departamento_id = $id;
        $this->carrera = $carrera->carrera;
    
        $this->openModal();
    }
     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        Carrera::find($id)->delete();
        session()->flash('message', 'Registro Eliminado correctamente!');
    }
}