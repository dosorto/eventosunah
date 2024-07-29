<?php

namespace App\Livewire\MarcarAsistencia;

use App\Models\Persona;
use App\Models\Conferencia;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asistencia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class MarcarAsistencias extends Component
{
    use WithPagination;

    public $Fecha, $Asistencia, $IdConferencia, $asistencia_id, $search;
    public $isOpen = false;

    public $personas;
    public $eventos;

    public $inputSearchPersona = '';
    public $inputSearchEvento = '';
    public $searchPersonas = [];
    public $searchEventos = [];

    public $fechaActual;
    public function mount()
    {
        $this->conferencia = Conferencia::all();
        $this->fechaActual = Carbon::now();

    }

    public function render()
    {
        return view('livewire.MarcarAsistencia.marcar-asistencia', [
            'conferencias' => Conferencia::all(),
            'asistencias' => Asistencia::with('persona', 'conferencia')->get(),
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
        $this->Fecha = Carbon::now();
        $this->Asistencia = '';
        $this->IdEvento = '';
    }

    public function store()
    {
        $this->validate([
            'Asistencia' => 'required|boolean',
            'IdConferencia' => 'required',
        ]);

        $user = Auth::user();
        $persona = $user->persona;

        if (!$persona) {
            // Manejar el caso donde no se encuentra la persona asociada al usuario
            throw new \Exception("No se encontró una persona asociada al usuario autenticado.");
        }

        Asistencia::updateOrCreate(['id' => $this->asistencia_id], [
            'Fecha' => $this->fechaActual,
            'Asistencia' => $this->Asistencia,
            'IdPersona' => $persona->id, // Captura el ID del usuario autenticado,
            'IdConferencia' => $this->IdConferencia,
        ]);

        session()->flash('message', $this->asistencia_id ? 'Asistencia actualizada correctamente!' : 'Asistencia creada correctamente!');

        $this->closeModal();
        $this->resetInputFields();

    }

    public function edit($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $this->asistencia_id = $id;
        $this->Fecha = $asistencia->fechaActual;
        $this->Asistencia = $asistencia->Asistencia;
        $this->IdPersona = $asistencia->IdPersona;
        $this->IdConferencia = $asistencia->IdConferencia;

        $persona = Persona::find($this->IdPersona);
        $conferencia = Conferencia::find($this->IdConferencia);

        $this->inputSearchPersona = $persona->nombre . ' ' . $persona->apellido;
        $this->inputSearchEvento = $conferencia->nombre;

        $this->openModal();
    }

    public function delete($id)
    {
        Asistencia::find($id)->delete();
        session()->flash('message', 'Registro de asistencia eliminado correctamente!');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'Fecha' => 'required',
            'Asistencia' => 'required',
            'IdConferencia' => 'required',
        ]);
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
        $this->inputSearchPersona = $persona ? $persona->nombre . ' ' . $persona->apellido : '';
        $this->searchPersonas = [];
    }

    public function updatedInputSearchEvento()
    {
        $this->searchEventos = Conferencia::where('nombre', 'like', '%' . $this->inputSearchEvento . '%')
            ->get();
    }

    public function selectEvento($conferenciaId)
    {
        $this->IdConferencia = $conferenciaId;
        $conferencia = Conferencia::find($conferenciaId);
        $this->inputSearchEvento = $conferencia->nombre;
        $this->searchEventos = [];
    }
}
