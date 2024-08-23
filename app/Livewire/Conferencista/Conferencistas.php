<?php

namespace App\Livewire\Conferencista;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Conferencista;
use App\Models\Nacionalidad;
use App\Models\Tipoperfil;
use App\Models\Persona;

class Conferencistas extends Component
{
    use WithPagination, WithFileUploads;

    public $titulo, $descripcion, $foto, $firma, $sello, $persona_id, $conferencista_id, $search;
    public $dni, $nombre, $apellido, $correo, $correoInstitucional, $fechaNacimiento, $sexo, $direccion, $telefono, $numeroCuenta, $IdNacionalidad, $IdTipoPerfil;
    public $nacionalidades, $tipoperfiles;

    public $isOpen = 0;
    public $currentFoto, $currentFirma, $currentSello; // Para mantener las fotos actuales

    protected $rules = [
        'titulo' => 'required',
        'descripcion' => 'required',
        'foto' => 'nullable|image|max:1024',
        'firma' => 'nullable|image|max:1024',
        'sello' => 'nullable|image|max:1024',
        'persona_id' => 'required|exists:personas,id',
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
        $this->persona_id = null;
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
        $this->currentFoto = null;
        $this->currentFirma = null;
        $this->currentSello = null;
    }

    public function store()
    {
        try {
            $this->validate([
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'firma' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'sello' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'dni' => 'required|string|max:20',
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'titulo' => 'required|string|max:255',
                'correo' => 'nullable|email|max:255',
                'fechaNacimiento' => 'nullable|date',
                'sexo' => 'nullable|string|max:10',
                'telefono' => 'nullable|string|max:20',
                'IdNacionalidad' => 'required|exists:nacionalidads,id',
                'descripcion' => 'nullable|string',
                'direccion' => 'nullable|string|max:255',
                'IdTipoPerfil' => 'required|exists:tipoperfils,id',
                'numeroCuenta' => 'nullable|string|max:20',
                'correoInstitucional' => 'nullable|email|max:255',
            ]);

            // Manejo de archivos
            $this->foto = $this->foto ? $this->foto->store('public/conferencistas') : ($this->conferencista_id ? $this->currentFoto : 'http://www.puertopixel.com/wp-content/uploads/2011/03/Fondos-web-Texturas-web-abtacto-17.jpg');
            $this->firma = $this->firma ? $this->firma->store('public/conferencistas') : $this->currentFirma;
            $this->sello = $this->sello ? $this->sello->store('public/conferencistas') : $this->currentSello;

            // Datos del usuario autenticado
            $createdBy = auth()->id();
            if (!$createdBy) {
                throw new \Exception('No user is authenticated.');
            }

            // Actualizar o crear la persona
            $persona = Persona::updateOrCreate(
                ['dni' => $this->dni], // Condición para actualizar
                [
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
                ]
            );

            // Datos del conferencista
            $dataConferencista = [
                'IdPersona' => $persona->id,
                'foto' => $this->foto ? str_replace('public/', 'storage/', $this->foto) : $this->currentFoto,
                'firma' => $this->firma ? str_replace('public/', 'storage/', $this->firma) : $this->currentFirma,
                'sello' => $this->sello ? str_replace('public/', 'storage/', $this->sello) : $this->currentSello,
                'titulo' => $this->titulo,
                'descripcion' => $this->descripcion,
            ];

            // Crear o actualizar el conferencista
            if ($this->conferencista_id) {
                $conferencista = Conferencista::findOrFail($this->conferencista_id);
                $conferencista->update($dataConferencista);
            } else {
                Conferencista::create($dataConferencista);
            }

            session()->flash('message', $this->conferencista_id ? 'Conferencista actualizado correctamente!' : 'Conferencista creado correctamente!');

            $this->closeModal();
            $this->resetInputFields();
            $this->render();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
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

        // Almacenar las URLs actuales de las fotos
        $this->currentFoto = $conferencista->foto;
        $this->currentFirma = $conferencista->firma;
        $this->currentSello = $conferencista->sello;

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

            $conferencista->forceDelete();
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
            session()->flash('error', 'No se puede eliminar el conferencista: '.$conferencista->persona->nombre .' '.$conferencista->persona->apellido .', porque está enlazado a una o más conferencias.');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $conferencista->persona->nombre . ' ' . $conferencista->persona->apellido;
        $this->confirmingDelete = true;
    }
}
