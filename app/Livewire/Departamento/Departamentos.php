<?php

namespace App\Livewire\Departamento;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Departamento;

class Departamentos extends Component
{
    use WithPagination;
    public $departamento, $departamento_id, $search;
    public $isOpen = 0;
    public function render()
    {
        $departamentos = Departamento::where('Departamento', 'like', '%'.$this->search.'%')->orderBy('id','ASC')->paginate(8);
        return view('livewire.Departamento.Departamentos', ['departamentos' =>$departamentos ]);
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
        $this->departamento = '';
    }

    public function store()
    {
        $this->validate([
            'Departamento' => 'required',
        ]);
   
        Departamento::updateOrCreate(['id' => $this->departamento_id], [
            'Departamento' => $this->departamento,
        ]);
  
        session()->flash('message', 
            $this->departamento_id ? 'Departamento Actualizada correctamente!' : 'Departamento creada correctamente!');
  
        $this->closeModal();
        $this->resetInputFields();
    }
    public function edit($id)
    {
        $departamento = Departamento::findOrFail($id);
        $this->departamento_id = $id;
        $this->departamento = $departamento->Departamento;
    
        $this->openModal();
    }
     
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        Departamento::find($id)->delete();
        session()->flash('message', 'Registro Eliminado correctamente!');
    }
}
