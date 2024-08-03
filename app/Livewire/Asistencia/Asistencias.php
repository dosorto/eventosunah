<?php

namespace App\Livewire\Asistencia;

use App\Models\Persona;
use App\Models\Conferencia;
use App\Models\Suscripcion;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asistencia;
use Carbon\Carbon;

class Asistencias extends Component
{
    use WithPagination;

    public $Fecha, $Asistencia, $IdSuscripcion, $asistencia_id, $search;
    public $isOpen = false;
    public $suscripciones;

    public $inputSearchSuscripcion = '';
    public $searchSuscripciones = [];
    public $fechaActual;

    public function updatedInputSearchSuscripcion()
    {
        $searchTerm = '%' . $this->inputSearchSuscripcion . '%';
        
        $this->searchSuscripciones = Suscripcion::whereHas('persona', function($query) use ($searchTerm) {
            $query->where('nombre', 'like', $searchTerm)
                ->orWhere('apellido', 'like', $searchTerm);
        })->orWhereHas('conferencia', function($query) use ($searchTerm) {
            $query->where('nombre', 'like', $searchTerm);
        })->get();
    }


    public function selectSuscripcion($suscripcionId)
    {
        $suscripcion = Suscripcion::find($suscripcionId);
        if ($suscripcion) {
            $this->IdSuscripcion = $suscripcion->id;
            $this->inputSearchSuscripcion = $suscripcion->persona->nombre . ' ' . $suscripcion->persona->apellido . ' - ' . $suscripcion->conferencia->nombre;
            $this->searchSuscripciones = [];
        }
    }

    public function mount()
    {
        $this->suscripciones = Suscripcion::all();
        $this->fechaActual = Carbon::now();
    }

    public function render()
    {
        $asistencias = Asistencia::with('suscripcion')
            ->where('Fecha', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);

        return view('livewire.asistencia.asistencias', [
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
        $this->IdSuscripcion = '';
        $this->asistencia_id = null;
        $this->inputSearchSuscripcion = '';
        $this->searchSuscripciones = [];
    }

    public function store()
    {
        $this->validate([
            'Asistencia' => 'required|boolean',
            'IdSuscripcion' => 'required|unique:asistencias,IdSuscripcion,' . $this->asistencia_id,
        ], [
            'IdSuscripcion.unique' => 'Esta suscripción ya tiene un registro de asistencia.',
        ]);

        Asistencia::updateOrCreate(['id' => $this->asistencia_id], [
            'Fecha' => $this->fechaActual,
            'Asistencia' => $this->Asistencia,
            'IdSuscripcion' => $this->IdSuscripcion,
        ]);

        session()->flash('message', $this->asistencia_id ? 'Asistencia actualizada correctamente!' : 'Asistencia creada correctamente!');

        $this->closeModal();
        $this->resetInputFields();
    }
    public $showDeleteModal = false;
    public $deleteId = null;
    
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
    
    public function deleteConfirmed()
    {
        Asistencia::find($this->deleteId)->delete();
        $this->deleteId = null;
        $this->showDeleteModal = false;
        session()->flash('message', 'Registro de asistencia eliminado correctamente!');
    }
    public function edit($id)
    {
        $asistencia = Asistencia::find($id);
        
        if ($asistencia) {
            $this->asistencia_id = $asistencia->id;
            $this->Fecha = $asistencia->Fecha;
            $this->Asistencia = $asistencia->Asistencia;
            $this->IdSuscripcion = $asistencia->IdSuscripcion;
            $this->inputSearchSuscripcion = $asistencia->suscripcion->persona->nombre . ' ' . $asistencia->suscripcion->persona->apellido . ' - ' . $asistencia->suscripcion->conferencia->nombre;
            $this->openModal();
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'Fecha' => 'required',
            'Asistencia' => 'required',
            'IdSuscripcion' => 'required',
        ]);
    }
}

