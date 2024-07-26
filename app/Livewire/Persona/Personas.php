<?php

namespace App\Livewire\Persona;

use App\Models\Nacionalidad;
use App\Models\Tipoperfil;
use App\Models\Persona;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Personas extends Component
{
    use WithPagination;

    public $persona_id, $IdUsuario, $dni, $nombre, $apellido, $correo, $correoInstitucional, $fechaNacimiento, $sexo, $direccion, $telefono, $numeroCuenta, $IdNacionalidad, $IdTipoPerfil, $search;
    public $isOpen = 0;

    public $user;
    public $nacionalidades;
    public $tipoperfiles;

    public function mount()
    {
        $this->user = User::all();
        $this->nacionalidades = Nacionalidad::all();
        $this->tipoperfiles = Tipoperfil::all();
    }

    public function render()
{
    $personas = Persona::with('user', 'nacionalidad', 'tipoperfil')
        ->where('nombre', 'like', '%' . $this->search . '%')
        ->orderBy('id', 'DESC')
        ->paginate(5);

    return view('livewire.persona.personas', [
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
        $this->IdUsuario = '';
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
    $this->validate([
        'IdUsuario' => 'required|integer|exists:users,id',
        'dni' => 'required|unique:personas,dni,' . $this->persona_id,
        'nombre' => 'required',
        'apellido' => 'required',
        'correo' => 'required|email|unique:personas,correo,' . $this->persona_id,
        'correoInstitucional' => 'nullable|email|unique:personas,correoInstitucional,' . $this->persona_id,
        'fechaNacimiento' => 'required|date',
        'sexo' => 'required',
        'direccion' => 'required',
        'telefono' => 'required',
        'numeroCuenta' => 'nullable|unique:personas,numeroCuenta,' . $this->persona_id,
        'IdNacionalidad' => 'required|exists:nacionalidads,id',
        'IdTipoPerfil' => 'required|exists:tipoperfils,id',
    ]);

    Persona::updateOrCreate(['id' => $this->persona_id], [
        'IdUsuario' => $this->IdUsuario,
        'dni' => $this->dni,
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
    $this->render(); // Recargar datos
}

    

    public function edit($id)
    {
        $persona = Persona::findOrFail($id);
        $this->persona_id = $id;
        $this->IdUsuario = $persona->IdUsuario;
        $this->dni = $persona->dni;
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

    public function delete($id)
    {
        Persona::find($id)->delete();
        session()->flash('message', 'Registro eliminado correctamente!');
        $this->render(); // Recargar datos
    }
}
