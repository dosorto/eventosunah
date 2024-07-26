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
    public $inputSearchPersona = '';
    public $searchPersonas = [];

    protected $rules = [
        'titulo' => 'required',
        'descripcion' => 'required',
        'foto' => 'nullable|image|max:1024', 
        'IdPersona' => 'required',
    ];
    public $personas;
    public function mount()
    {
        $this->personas = Persona::all();
    }

    public function render()
    {
        $conferencistas = Conferencista::with('persona')
            ->where('Titulo', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'DESC')
            ->paginate(5);

        return view('livewire.Conferencista.conferencistas', ['conferencistas' => $conferencistas]);
    }

    public function updatedInputSearchPersona()
    {
        $this->searchPersonas = Persona::where('nombre', 'like', '%' . $this->inputSearchPersona . '%')
            ->orWhere('apellido', 'like', '%' . $this->inputSearchPersona . '%')
            ->get();
    }

    public function selectPersona($personaId)
    {
        $this->IdPersona = $personaId;
        $persona = Persona::find($personaId);
        $this->inputSearchPersona = $persona->nombre . ' ' . $persona->apellido;
        $this->searchPersonas = [];
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
        $this->inputSearchPersona = '';
        $this->searchPersonas = [];
    }

    public function store()
    {
        $this->validate([
            'titulo' => 'required',
            'descripcion' => 'required',
            'foto' => 'nullable|image|max:1024',
            'IdPersona' => [
                'required',
                'exists:personas,id',
                function ($attribute, $value, $fail) {
                    $conferencista = Conferencista::where('IdPersona', $value)
                        ->where('id', '<>', $this->conferencista_id)
                        ->first();
                    
                    if ($conferencista) {
                        $fail('El ID de la persona ya está asignado a otro conferencista.');
                    }
                },
            ],
        ]);

        // Subir la foto si se seleccionó una nueva
        if ($this->foto) {
            $this->foto = $this->foto->store('public/conferencistas');
        } elseif ($this->conferencista_id) {
            // Si no se seleccionó una nueva foto pero se está editando, mantener la foto actual
            $conferencista = Conferencista::findOrFail($this->conferencista_id);
            $this->foto = $conferencista->Foto;
        }

        Conferencista::updateOrCreate(['id' => $this->conferencista_id], [
            'Titulo' => $this->titulo,
            'Descripcion' => $this->descripcion,
            'Foto' => $this->foto ? str_replace('public/', 'storage/', $this->foto) : null,
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
        $this->IdPersona = $conferencista->IdPersona;

        // Solo se necesita el ID para buscar la persona, no asignar la foto aquí
        $persona = Persona::find($this->IdPersona);
        if ($persona) {
            $this->inputSearchPersona = $persona->nombre . ' ' . $persona->apellido;
        }

        $this->openModal();
    }

    public function delete($id)
    {
        Conferencista::find($id)->delete();
        session()->flash('message', 'Conferencista eliminado correctamente!');
    }
}