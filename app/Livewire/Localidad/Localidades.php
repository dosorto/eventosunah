<?php

namespace App\Livewire\Localidad;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Localidad;
use App\Models\Evento;
class Localidades extends Component
{
    use WithPagination;
    public $localidad, $localidad_id, $search;
    public $isOpen = 0;
    public function render()
    {
        $localidades = Localidad::where('localidad', 'like', '%'.$this->search.'%')->orderBy('id','DESC')->paginate(8);
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
            'localidad' => [
                'required',
                'string',
                'max:255',
                'unique:localidads,localidad,' . $this->localidad_id,
            ],
        ]);
    
        Localidad::updateOrCreate(
            ['id' => $this->localidad_id],
            ['localidad' => $this->localidad]
        );
    
        session()->flash('message', 
            $this->localidad_id ? 'Localidad actualizada correctamente!' : 'Localidad creada correctamente!'
        );
    
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
       
        $eventosAsociados = Evento::where('IdLocalidad', $id)->exists();
    
        if ($eventosAsociados) {
            session()->flash('message', 'No se puede eliminar la localidad porque está asociada a uno o más eventos.');
        } else {
          
            Localidad::find($id)->delete();
            session()->flash('message', 'Localidad eliminada correctamente!');
        }
    }
}
