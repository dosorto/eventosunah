<?php

namespace App\Livewire\Conferencista;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Conferencista;
use App\Models\Nacionalidad;
use App\Models\Tipoperfil;

class Conferencistas extends Component
{
    use WithPagination, WithFileUploads;

    public $titulo, $descripcion, $foto, $dni, $nombre, $apellido, $correo, $correoInstitucional, $fechaNacimiento, $sexo, $direccion, $telefono, $numeroCuenta, $IdNacionalidad, $IdTipoPerfil, $conferencista_id, $search;
    public $isOpen = 0;

    protected $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'dni' => 'required|string|max:20',
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'correo' => 'required|email|max:255',
        'correoInstitucional' => 'nullable|email|max:255',
        'fechaNacimiento' => 'nullable|date',
        'sexo' => 'nullable|string|max:10',
        'direccion' => 'nullable|string|max:255',
        'telefono' => 'nullable|string|max:20',
        'numeroCuenta' => 'nullable|string|max:20',
        'IdNacionalidad' => 'required|exists:nacionalidads,id',
        'IdTipoPerfil' => 'required|exists:tipoperfils,id',
    ];

    public $nacionalidades;
    public $tipoperfiles;

    public function mount()
    {
        $this->nacionalidades = Nacionalidad::all();
        $this->tipoperfiles = Tipoperfil::all();
    }

    public function render()
    {
        $conferencistas = Conferencista::with('nacionalidad', 'tipoPerfil')
            ->where('Titulo', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'ASC')
            ->paginate(5);

        return view('livewire.Conferencista.conferencistas', ['conferencistas' => $conferencistas]);
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
        $this->foto = '';
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
        try {
            // Validar datos
            $this->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'dni' => 'required|string|max:20',
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'correo' => 'required|email|max:255',
                'correoInstitucional' => 'nullable|email|max:255',
                'fechaNacimiento' => 'nullable|date',
                'sexo' => 'nullable|string|max:10',
                'direccion' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'numeroCuenta' => 'nullable|string|max:20',
                'IdNacionalidad' => 'required|exists:nacionalidads,id',
                'IdTipoPerfil' => 'required|exists:tipoperfils,id',
            ]);
    
            // Manejo de la foto
            if ($this->foto) {
                $this->foto = $this->foto->store('public/conferencistas');
            } else {
                $this->foto = null;
            }
    
            // Verifica el valor de auth()->id()
            $createdBy = auth()->id();
            if (!$createdBy) {
                throw new \Exception('No user is authenticated.');
            }
    
            // Datos para guardar
            $data = [
                'Titulo' => $this->titulo,
                'Descripcion' => $this->descripcion,
                'Foto' => $this->foto ? str_replace('public/', 'storage/', $this->foto) : null,
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
                'created_by' => $createdBy,
            ];
    
            // Guardar o actualizar el conferencista
            if ($this->conferencista_id) {
                $conferencista = Conferencista::findOrFail($this->conferencista_id);
                $conferencista->update($data);
            } else {
                Conferencista::create($data);
            }
    
            // Mensaje de éxito
            session()->flash('message', $this->conferencista_id ? 'Conferencista actualizado correctamente!' : 'Conferencista creado correctamente!');
    
            // Cerrar el modal y reiniciar los campos
            $this->closeModal();
            $this->resetInputFields();
        } catch (\Exception $e) {
            dd($e->getMessage()); // Muestra el mensaje de error si algo sale mal
        }
    }
    

    public function edit($id)
    {
        $conferencista = Conferencista::findOrFail($id);
        $this->conferencista_id = $id;
        $this->titulo = $conferencista->Titulo;
        $this->descripcion = $conferencista->Descripcion;
        $this->dni = $conferencista->dni;
        $this->nombre = $conferencista->nombre;
        $this->apellido = $conferencista->apellido;
        $this->correo = $conferencista->correo;
        $this->correoInstitucional = $conferencista->correoInstitucional;
        $this->fechaNacimiento = $conferencista->fechaNacimiento;
        $this->sexo = $conferencista->sexo;
        $this->direccion = $conferencista->direccion;
        $this->telefono = $conferencista->telefono;
        $this->numeroCuenta = $conferencista->numeroCuenta;
        $this->IdNacionalidad = $conferencista->IdNacionalidad;
        $this->IdTipoPerfil = $conferencista->IdTipoPerfil;

        $this->openModal();
    }

    public function delete($id)
    {
        Conferencista::find($id)->delete();
        session()->flash('message', 'Conferencista eliminado correctamente!');
    }
}
?>
