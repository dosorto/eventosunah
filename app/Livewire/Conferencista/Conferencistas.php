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

    public $titulo, $descripcion, $foto, $persona_id, $conferencista_id, $search;
    public $isOpen = 0;
    public $inputSearchPersona = '';
    public $searchPersonas = [];

    protected $rules = [
        'titulo' => 'required',
        'descripcion' => 'required',
        'foto' => 'nullable|image|max:1024',
        'persona_id' => 'required|exists:personas,id',
    ];

    public function mount()
    {
        // Aquí se podría realizar alguna inicialización si es necesario
    }

    public function render()
    {
        $conferencistas = Conferencista::with('persona')
            ->where('titulo', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'ASC')
            ->paginate(5);

        return view('livewire.conferencista.conferencistas', ['conferencistas' => $conferencistas]);
    }

    public function updatedInputSearchPersona()
    {
        $this->searchPersonas = Persona::where('nombre', 'like', '%' . $this->inputSearchPersona . '%')
            ->orWhere('apellido', 'like', '%' . $this->inputSearchPersona . '%')
            ->get();
    }

    public function selectPersona($personaId)
    {
        $this->persona_id = $personaId;
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
        $this->foto = null;
        $this->persona_id = null;
        $this->conferencista_id = null;
        $this->inputSearchPersona = '';
        $this->searchPersonas = [];
    }

    public function store()
    {
        $this->validate();

        // Subir la foto si se seleccionó una nueva
        if ($this->foto) {
            $this->foto = $this->foto->store('public/conferencistas'); // Guarda la imagen en storage/public/conferencistas
        } elseif ($this->conferencista_id) {
            // Si no se seleccionó una nueva foto pero se está editando, mantener la foto actual
            $conferencista = Conferencista::findOrFail($this->conferencista_id);
            $this->foto = $conferencista->foto;
        }

        Conferencista::updateOrCreate(['id' => $this->conferencista_id], [
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'foto' => $this->foto ? str_replace('public/', 'storage/', $this->foto) : null,
            'persona_id' => $this->persona_id
        ]);

        session()->flash('message', $this->conferencista_id ? 'Conferencista actualizado correctamente!' : 'Conferencista creado correctamente!');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $conferencista = Conferencista::findOrFail($id);
        $this->conferencista_id = $id;
        $this->titulo = $conferencista->titulo;
        $this->descripcion = $conferencista->descripcion;
        $this->persona_id = $conferencista->persona_id;

        // Solo se necesita el ID para buscar la persona, no asignar la foto aquí
        $persona = Persona::find($this->persona_id);
        if ($persona) {
            $this->inputSearchPersona = $persona->nombre . ' ' . $persona->apellido;
        }

        $this->openModal();
    }

    public function delete($id)
    {
        $conferencista = Conferencista::findOrFail($id);
        if ($conferencista->foto) {
            \Storage::delete('public/conferencistas/' . basename($conferencista->foto));
        }
        $conferencista->delete();
        session()->flash('message', 'Conferencista eliminado correctamente!');
    }
}
