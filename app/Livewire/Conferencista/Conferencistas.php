<?php

namespace App\Livewire\Conferencista;

use App\Models\Persona;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Conferencista;

class Conferencistas extends Component
{
    use WithPagination, WithFileUploads;

    public $titulo, $descripcion, $foto, $IdPersona, $conferencista_id, $search;
    public $isOpen = 0;
    public $personas;

    protected $rules = [
        'titulo' => 'required',
        'descripcion' => 'required',
        'foto' => 'nullable|image|max:1024', // Ajusta el tamaño máximo según tus necesidades
        'IdPersona' => 'required',
    ];

    public function mount()
    {
        $this->personas = Persona::all();
    }

    public function render()
    {
        $conferencistas = Conferencista::with('persona')
            ->where('Titulo', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'ASC')
            ->paginate(5);

        return view('livewire.conferencista.conferencistas', ['conferencistas' => $conferencistas]);
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
        $this->titulo = '';
        $this->descripcion = '';
        $this->foto = '';
        $this->IdPersona = '';
        $this->conferencista_id = null;
    }

    public function store()
    {
        $this->validate();

        // Subir la foto si se seleccionó una
        if ($this->foto) {
            $this->foto = $this->foto->store('public/conferencistas'); // Guarda la imagen en storage/public/conferencistas
        }

        Conferencista::updateOrCreate(['id' => $this->conferencista_id], [
            'Titulo' => $this->titulo,
            'Descripcion' => $this->descripcion,
            'Foto' => $this->foto ? str_replace('public/', 'storage/', $this->foto) : null, // Ajusta la ruta de almacenamiento según tus necesidades
            'IdPersona' => $this->IdPersona
        ]);

        session()->flash('message', $this->conferencista_id ? 'Conferencista actualizado correctamente!' : 'Conferencista creado correctamente!');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $conferencista = Conferencista::findOrFail($id);
        $this->conferencista_id = $id;
        $this->titulo = $conferencista->Titulo;
        $this->descripcion = $conferencista->Descripcion;
        $this->foto = $conferencista->Foto;
        $this->IdPersona = $conferencista->IdPersona;

        $this->openModal();
    }

    public function delete($id)
    {
        Conferencista::find($id)->delete();
        session()->flash('message', 'Conferencista eliminado correctamente!');
    }
}
