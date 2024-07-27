<?php

namespace App\Livewire\Asistencia;

use App\Models\Persona;
use App\Models\Evento;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asistencia;
use Carbon\Carbon;

class Asistencias extends Component
{
    use WithPagination;

    public $Fecha, $Asistencia, $IdPersona, $IdEvento, $asistencia_id, $search;
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
        $this->personas = Persona::all();
        $this->eventos = Evento::all();
        $this->fechaActual = Carbon::now();
        
    }

    public function render()
    {
        $asistencias = Asistencia::with(['persona', 'evento'])
            ->where('Fecha', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);

        return view('livewire.Asistencia.asistencias', [
            'asistencias' => $asistencias,
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
        $this->IdPersona = '';
        $this->IdEvento = '';
        $this->asistencia_id = null;
        $this->inputSearchPersona = '';
        $this->inputSearchEvento = '';
        $this->searchPersonas = [];
        $this->searchEventos = [];
    }

    public function store()
    {
        $this->validate([
            'Asistencia' => 'required|boolean',
            'IdPersona' => 'required',
            'IdEvento' => 'required',
        ]);

        Asistencia::updateOrCreate(['id' => $this->asistencia_id], [
            'Fecha' => $this->fechaActual,
            'Asistencia' => $this->Asistencia,
            'IdPersona' => $this->IdPersona,
            'IdEvento' => $this->IdEvento,
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
        $this->IdEvento = $asistencia->IdEvento;

        $persona = Persona::find($this->IdPersona);
        $evento = Evento::find($this->IdEvento);

        $this->inputSearchPersona = $persona->nombre . ' ' . $persona->apellido;
        $this->inputSearchEvento = $evento->nombreevento;

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
            'IdPersona' => 'required',
            'IdEvento' => 'required',
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
        $this->searchEventos = Evento::where('nombreevento', 'like', '%' . $this->inputSearchEvento . '%')
            ->get();
    }

    public function selectEvento($eventoId)
    {
        $this->IdEvento = $eventoId;
        $evento = Evento::find($eventoId);
        $this->inputSearchEvento = $evento->nombreevento;
        $this->searchEventos = [];
    }
}
