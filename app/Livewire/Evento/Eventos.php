<?php

namespace App\Livewire\Evento;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Evento;
use App\Models\Cuenta;
use App\Models\Modalidad;
use App\Models\Localidad;
use App\Models\Diploma;

class Eventos extends Component
{
    use WithPagination, WithFileUploads;

    public $logo, $nombreevento, $estado,$precio, $descripcion, $organizador, $fechainicio, $fechafinal, $horainicio, $horafin, $idmodalidad, $idlocalidad, $IdDiploma, $IdCuenta, $evento_id, $search;
    public $isOpen = 0;
    public $confirmingDelete = false;
    public $eventoIdAEliminar;
    public $nombreEventoAEliminar;
    public $showDetails = false;
    public $selectedEvento;
    public $modalidades, $localidades, $diplomas;
    public $cuentas, $createNewCuenta = false, $cuentaNombre, $cuentaNumeroDeCuenta, $cuentaBanco, $cuentaTipoCuenta, $cuentaSaldoActual;

    public function mount()
    {
        $this->modalidades = Modalidad::all();
        $this->localidades = Localidad::all();
        $this->diplomas = Diploma::all();
        $this->cuentas = Cuenta::all();
    }

    public function render()
    {
        $eventos = Evento::with('modalidad', 'localidad', 'diploma', 'cuenta')
            ->where('nombreevento', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);

        return view('livewire.evento.eventos', ['eventos' => $eventos]);
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
        $this->logo = '';
        $this->nombreevento = '';
        $this->descripcion = '';
        $this->organizador = '';
        $this->fechainicio = '';
        $this->fechafinal = '';
        $this->horainicio = '';
        $this->horafin = '';
        $this->idmodalidad = '';
        $this->idlocalidad = '';
        $this->IdDiploma = '';
        $this->IdCuenta = '';
        $this->estado = '';
        $this->precio = '';
        $this->createNewCuenta = false;
        $this->cuentaNombre = '';
        $this->cuentaNumeroDeCuenta = '';
        $this->cuentaBanco = '';
        $this->cuentaTipoCuenta = '';
        $this->cuentaSaldoActual = 0;
    }

    public function store()
    {
        $this->validate([
            'logo' => 'nullable|image',
            'nombreevento' => 'required',
            'descripcion' => 'required',
            'organizador' => 'required',
            'fechainicio' => 'required',
            'fechafinal' => 'required',
            'horainicio' => 'required',
            'horafin' => 'required',
            'idmodalidad' => 'required',
            'idlocalidad' => 'required',
            'IdDiploma' => 'required',
            'IdCuenta' => 'nullable|exists:cuentas,id',
            'estado' => 'required|string|max:255',
            'precio' => 'nullable',
        ]);

        // Manejo de archivo logo
        if ($this->logo) {
            $this->logo = $this->logo->store('public/eventos');
        } elseif ($this->evento_id) {
            $evento = Evento::findOrFail($this->evento_id);
            $this->logo = $evento->logo; 
        }

        // Si se está creando una nueva cuenta
        if ($this->createNewCuenta) {
            $this->validate([
                'cuentaNombre' => 'required|string',
                'cuentaNumeroDeCuenta' => 'required|string|unique:cuentas,numeroDeCuenta',
                'cuentaBanco' => 'required|string',
                'cuentaTipoCuenta' => 'required|string',
                'cuentaSaldoActual' => 'required|numeric',
            ]);

            $cuenta = Cuenta::create([
                'numeroDeCuenta' => $this->cuentaNumeroDeCuenta,
                'CuentaHabiente' => $this->cuentaNombre,
                'Banco' => $this->cuentaBanco,
                'TipoCuenta' => $this->cuentaTipoCuenta,
                'saldoActual' => $this->cuentaSaldoActual,
                'created_by' => auth()->id(),
            ]);

            $this->IdCuenta = $cuenta->id;
        }

        Evento::updateOrCreate(['id' => $this->evento_id], [
            'logo' => $this->logo ? str_replace('public/', 'storage/', $this->logo) : null,
            'nombreevento' => $this->nombreevento,
            'descripcion' => $this->descripcion,
            'organizador' => $this->organizador,
            'fechainicio' => $this->fechainicio,
            'fechafinal' => $this->fechafinal,
            'horainicio' => $this->horainicio,
            'horafin' => $this->horafin,
            'idmodalidad' => $this->idmodalidad,
            'idlocalidad' => $this->idlocalidad,
            'IdDiploma' => $this->IdDiploma,
            'IdCuenta' => $this->IdCuenta ?: null,
            'estado' => $this->estado,
            'precio' => $this->precio ?: null,
        ]);

        session()->flash('message', 
            $this->evento_id ? 'Evento creado correctamente!' : 'Evento actualizado correctamente!');

        $this->closeModal();
        $this->resetInputFields();
    }


    public function edit($id)
{
    $evento = Evento::findOrFail($id);
    $this->evento_id = $id;
    $this->nombreevento = $evento->nombreevento;
    $this->descripcion = $evento->descripcion;
    $this->organizador = $evento->organizador;
    $this->fechainicio = $evento->fechainicio;
    $this->fechafinal = $evento->fechafinal;
    $this->horainicio = $evento->horainicio;
    $this->horafin = $evento->horafin;
    $this->idmodalidad = $evento->idmodalidad;
    $this->idlocalidad = $evento->idlocalidad;
    $this->IdDiploma = $evento->IdDiploma;
    $this->IdCuenta = $evento->IdCuenta;
    $this->logo = null; // Mantener el logo existente sin sobrescribirlo
    $this->estado = $evento->estado;
    $this->precio = $evento->precio;
    $this->openModal();
}

    public function delete()
    {
        if ($this->confirmingDelete) {
            $evento = Evento::find($this->eventoIdAEliminar);

            if (!$evento) {
                session()->flash('error', 'Evento no encontrado.');
                return;
            }

            $evento->delete();
            session()->flash('message', 'Evento eliminado correctamente!');
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $evento = Evento::find($id);
        if ($evento->conferencias()->exists()) {
            session()->flash('error', 'No se puede eliminar el evento: ' .$evento->nombreevento .', porque está enlazado a una o más conferencias.');
            return;
        }

        $this->eventoIdAEliminar = $id;
        $this->nombreEventoAEliminar = $evento->nombreevento;
        $this->confirmingDelete = true;
    }

    public function viewDetails($id)
    {
        $this->selectedEvento = Evento::find($id);
        $this->showDetails = true;
    }

    public function closeDetails()
    {
        $this->showDetails = false;
    }

    public function toggleCreateNewCuenta()
    {
        $this->createNewCuenta = !$this->createNewCuenta;
        if (!$this->createNewCuenta) {
            // Si se desactiva la creación de una nueva cuenta, restablecer los campos
            $this->cuentaNombre = '';
            $this->cuentaNumeroDeCuenta = '';
            $this->cuentaBanco = '';
            $this->cuentaTipoCuenta = '';
            $this->cuentaSaldoActual = 0;
        }
    }
}
