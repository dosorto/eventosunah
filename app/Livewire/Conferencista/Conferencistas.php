<?php

namespace App\Livewire\Conferencista;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Conferencista;
use App\Models\Nacionalidad;
use App\Models\Tipoperfil;
use App\Models\Persona;
use Illuminate\Support\Facades\Log;

class Conferencistas extends Component
{
    use WithPagination, WithFileUploads;

    public $titulo, $descripcion, $foto, $firma, $sello, $persona_id, $conferencista_id, $search;
    public $dni, $nombre, $apellido, $correo, $correoInstitucional, $fechaNacimiento, $sexo, $direccion, $telefono, $numeroCuenta, $IdNacionalidad, $IdTipoPerfil;
    public $nacionalidades, $tipoperfiles;

    public $isOpen = 0;

    protected $rules = [
        'titulo' => 'required',
        'descripcion' => 'required',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'persona_id' => 'required|exists:personas,id',
        'firma' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'sello' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    public function mount()
    {
        $this->nacionalidades = Nacionalidad::all();
        $this->tipoperfiles = Tipoperfil::all();
    }

    public function render()
    {
        $conferencistas = Conferencista::with('persona')
            ->where('titulo', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'ASC')
            ->paginate(5);

        return view('livewire.Conferencista.conferencistas', ['conferencistas' => $conferencistas]);
    }

    public function selectPersona($personaId)
    {
        $this->persona_id = $personaId;
        $persona = Persona::find($personaId);
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
        $this->conferencista_id = '';
        $this->titulo = '';
        $this->descripcion = '';
        $this->foto = null;
        $this->firma = null;
        $this->sello = null;
        $this->persona_id = '';
        $this->dni = '';
        $this->nombre = '';
        $this->apellido = '';
        $this->correo = '';
        $this->correoInstitucional = '';
        $this->fechaNacimiento = '';
        $this->sexo = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->numeroCuenta = '';
        $this->IdNacionalidad = '';
        $this->IdTipoPerfil = '';
    }

    public function store()
    {
        $this->validate();

        try {
            // Manejo de la foto
            if ($this->foto) {
                $this->foto = $this->foto->store('public/conferencistas');
            } else {
                $this->foto = 'http://www.puertopixel.com/wp-content/uploads/2011/03/Fondos-web-Texturas-web-abtacto-17.jpg';
            }

            // Manejo de firma
            if ($this->firma) {
                $this->firma = $this->firma->store('public/firmas');
            } elseif ($this->conferencista_id) {
                $conferencista = Conferencista::findOrFail($this->conferencista_id);
                $this->firma = $conferencista->firma;
            } else {
                $this->firma = null;
            }

            // Manejo de sello
            if ($this->sello) {
                $this->sello = $this->sello->store('public/sellos');
            } elseif ($this->conferencista_id) {
                $conferencista = Conferencista::findOrFail($this->conferencista_id);
                $this->sello = $conferencista->sello;
            } else {
                $this->sello = null;
            }

            // Verifica el valor de auth()->id()
            $createdBy = auth()->id();
            if (!$createdBy) {
                throw new \Exception('No user is authenticated.');
            }

            // Datos para guardar
            $dataPersona = [
                'dni' => $this->dni,
                'nombre' => $this->nombre,
                'apellido' => $this->apellido,
                'correo' => $this->correo,
                'fechaNacimiento' => $this->fechaNacimiento,
                'sexo' => $this->sexo,
                'telefono' => $this->telefono,
                'IdNacionalidad' => $this->IdNacionalidad,
                'direccion' => $this->direccion,
                'IdTipoPerfil' => $this->IdTipoPerfil,
                'correoInstitucional' => $this->correoInstitucional ?: null,
                'numeroCuenta' => $this->numeroCuenta ?: null,
                'created_by' => $createdBy,
            ];

            $persona = Persona::updateOrCreate(['id' => $this->persona_id], $dataPersona);

            $dataConferencista = [
                'IdPersona' => $persona->id,
                'foto' => $this->foto ? str_replace('public/', 'storage/', $this->foto) : null,
                'titulo' => $this->titulo,
                'firma' => $this->firma ? str_replace('public/', 'storage/', $this->firma) : null,
                'sello' => $this->sello ? str_replace('public/', 'storage/', $this->sello) : null,
                'descripcion' => $this->descripcion,
            ];

            // Guardar o actualizar el conferencista
            if ($this->conferencista_id) {
                $conferencista = Conferencista::findOrFail($this->conferencista_id);
                $conferencista->update($dataConferencista);
            } else {
                Conferencista::create($dataConferencista);
            }

            // Mensaje de éxito
            session()->flash('message', $this->conferencista_id ? 'Conferencista actualizado correctamente!' : 'Conferencista creado correctamente!');

            // Cerrar el modal y reiniciar los campos
            $this->closeModal();
            $this->resetInputFields();
        } catch (\Exception $e) {
            // Mostrar el mensaje de error si algo sale mal
            session()->flash('error', 'Error: ' . $e->getMessage());
            Log::error('Error en el proceso de almacenar conferencista: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $conferencista = Conferencista::findOrFail($id);

        $this->conferencista_id = $id;
        $this->titulo = $conferencista->titulo;
        $this->descripcion = $conferencista->descripcion;
        $this->dni = $conferencista->persona->dni;
        $this->nombre = $conferencista->persona->nombre;
        $this->apellido = $conferencista->persona->apellido;
        $this->correo = $conferencista->persona->correo;
        $this->correoInstitucional = $conferencista->persona->correoInstitucional;
        $this->fechaNacimiento = $conferencista->persona->fechaNacimiento;
        $this->sexo = $conferencista->persona->sexo;
        $this->direccion = $conferencista->persona->direccion;
        $this->telefono = $conferencista->persona->telefono;
        $this->numeroCuenta = $conferencista->persona->numeroCuenta;
        $this->IdNacionalidad = $conferencista->persona->IdNacionalidad;
        $this->IdTipoPerfil = $conferencista->persona->IdTipoPerfil;

        // Cargar la información para editar
        $this->foto = $conferencista->foto;
        $this->firma = $conferencista->firma;
        $this->sello = $conferencista->sello;

        $this->openModal();
    }

    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;

    public function delete()
    {
        if ($this->confirmingDelete) {
            $conferencista = Conferencista::find($this->IdAEliminar);

            if (!$conferencista) {
                session()->flash('error', 'Conferencista no encontrado.');
                $this->confirmingDelete = false;
                return;
            }

            if ($conferencista->conferencias()->exists()) {
                session()->flash('error', 'No se puede eliminar el conferencista: ' . $conferencista->persona->nombre . ' ' . $conferencista->persona->apellido . ', porque está enlazado a una o más conferencias.');
                return;
            }

            $conferencista->delete();
            session()->flash('message', 'Conferencista eliminado correctamente!');
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $conferencista = Conferencista::find($id);

        if (!$conferencista) {
            session()->flash('error', 'Conferencista no encontrado.');
            return;
        }

        if ($conferencista->conferencias()->exists()) {
            session()->flash('error', 'No se puede eliminar el conferencista: ' . $conferencista->persona->nombre . ' ' . $conferencista->persona->apellido . ', porque está enlazado a una o más conferencias.');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $conferencista->persona->nombre . ' ' . $conferencista->persona->apellido;
        $this->confirmingDelete = true;
    }
}
