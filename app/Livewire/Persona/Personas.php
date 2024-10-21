<?php

namespace App\Livewire\Persona;

use App\Models\Nacionalidad;
use App\Models\Tipoperfil;
use App\Models\Persona;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class Personas extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $email, $password; // Campos para el usuario (opcional)
    public $persona_id, $foto, $IdUsuario, $dni, $nombre, $apellido, $correo, $correoInstitucional, $fechaNacimiento, $sexo, $direccion, $telefono, $numeroCuenta, $IdNacionalidad, $IdTipoPerfil, $search;
    public $isOpen = 0;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;
    public $user;
    public $nacionalidades;
    public $tipoperfiles;

    public function mount()
    {
        $this->nacionalidades = Nacionalidad::all();
        $this->tipoperfiles = Tipoperfil::all();
    }

    public function render()
    {
        $personas = Persona::with('user', 'nacionalidad', 'tipoperfil')
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(5);

        return view('livewire.Persona.personas', [
            'personas' => $personas,
            'nacionalidades' => $this->nacionalidades,
            'tipoperfiles' => $this->tipoperfiles
        ]);
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
        $this->persona_id = '';
        $this->IdUsuario = ''; // No es necesario para persona
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->dni = '';
        $this->foto = '';
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
    // Validar solo si el usuario debe ser creado
    $userValidation = [
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'password' => 'nullable|string|min:8',
    ];

    // Validaciones para el campo de persona
    $personaValidation = [
        'dni' => 'required|unique:personas,dni,' . $this->persona_id,
        'foto' => $this->persona_id ? 'nullable|image|mimes:jpeg,png,jpg,gif,jfif' : 'required|image|mimes:jpeg,png,jpg,gif,jfif',
        'nombre' => 'required',
        'apellido' => 'required',
        'correo' => 'required|email|unique:personas,correo,' . $this->persona_id,
        'correoInstitucional' => 'nullable|email',
        'fechaNacimiento' => 'required|date',
        'sexo' => 'required',
        'direccion' => 'required',
        'telefono' => 'required',
        'numeroCuenta' => 'nullable',
        'IdNacionalidad' => 'required|exists:nacionalidads,id',
        'IdTipoPerfil' => 'required|exists:tipoperfils,id',
    ];

    $this->validate($personaValidation);

    if ($this->foto) {
        $path = $this->foto->store('persona', 'public');
    } else {
        // Si no se ha subido una nueva foto, conservar la foto existente
        $persona = Persona::find($this->persona_id);
        $path = $persona ? $persona->foto : null; // Mantener la foto existente
    }

    // Solo crear el usuario si se proporciona el nombre, email y password
    if ($this->name && $this->email && $this->password) {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $role = Role::where('name', 'Participante')->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }
    } else {
        $user = null;
    }
    
    // Crear o actualizar la persona
    $persona = Persona::updateOrCreate(['id' => $this->persona_id], [
        'IdUsuario' => $user ? $user->id : ($this->IdUsuario ?: null),
        'dni' => $this->dni,
        'foto' => $path,
        'nombre' => $this->nombre,
        'apellido' => $this->apellido,
        'correo' => $this->correo,
        'correoInstitucional' => $this->correoInstitucional,
        'fechaNacimiento' => $this->fechaNacimiento,
        'sexo' => $this->sexo,
        'direccion' => $this->direccion,
        'telefono' => $this->telefono,
        'numeroCuenta' => $this->numeroCuenta,
        'IdNacionalidad' => $this->IdNacionalidad,
        'IdTipoPerfil' => $this->IdTipoPerfil,
    ]);

    session()->flash('message', $this->persona_id ? 'Persona actualizada correctamente!' : 'Persona creada correctamente!');
    $this->closeModal();
    $this->resetInputFields();
}


    public function edit($id)
    {
        $persona = Persona::findOrFail($id);
        $this->persona_id = $id;
        $this->IdUsuario = $persona->IdUsuario;
        $this->dni = $persona->dni;
        $this->foto = null; // No asignes la ruta aquí
        $this->nombre = $persona->nombre;
        $this->apellido = $persona->apellido;
        $this->correo = $persona->correo;
        $this->correoInstitucional = $persona->correoInstitucional;
        $this->fechaNacimiento = $persona->fechaNacimiento;
        $this->sexo = $persona->sexo;
        $this->direccion = $persona->direccion;
        $this->telefono = $persona->telefono;
        $this->numeroCuenta = $persona->numeroCuenta;
        $this->IdNacionalidad = $persona->IdNacionalidad;
        $this->IdTipoPerfil = $persona->IdTipoPerfil;

        $this->openModal();
    }


    public function delete()
    {
        if ($this->confirmingDelete) {
            $persona = Persona::find($this->IdAEliminar);

            if (!$persona) {
                session()->flash('error', 'Persona no encontrada.');
                $this->confirmingDelete = false;
                return;
            }

            $persona->forceDelete();
            session()->flash('message', 'Persona eliminada correctamente!');
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $persona = Persona::find($id);

        if (!$persona) {
            session()->flash('error', 'Persona no encontrada.');
            return;
        }
        if ($persona->conferencistas()->exists())  {
            session()->flash('error', 'No se puede eliminar la persona: ' . $persona->nombre . ' ' . $persona->apellido . ', porque está enlazada a un conferencista.');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $persona->nombre . ' ' . $persona->apellido;
        $this->confirmingDelete = true;
    }
}
