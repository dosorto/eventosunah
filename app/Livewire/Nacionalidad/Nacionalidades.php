<?php

namespace App\Livewire\Nacionalidad;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Nacionalidad;

class Nacionalidades extends Component
{
    use WithPagination;
    public $nombreNacionalidad, $nacionalidad_id, $search;
    public $isOpen = 0;
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
     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        Nacionalidad::find($id)->delete();
        session()->flash('message', 'Registro Eliminado correctamente!');
    }
}
