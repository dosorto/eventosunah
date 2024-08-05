<?php

namespace App\Livewire\Diploma;

use App\Models\Persona;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Diploma;
use App\Models\Conferencia;
use App\Models\Firma;
use App\Models\Evento;

class Diplomas extends Component
{
    use WithPagination, WithFileUploads;

    public $codigo,
        $URL,
        $Fecha,
        $IdConferencia,
        $IdFirma,
        $IdEvento,
        $diploma_id,
        $search;

    public $isOpen = false;

    public $inputSearchConferencia = '';
    public $searchConferencias = [];


    public $inputSearchFirma = '';
    public $searchFirmas = [];


    // public $searchPersonas = [];

    public $inputSearchEvento = '';
    public $searchEventos = [];

    protected $rules = [
        'codigo' => 'required',
        'URL' => 'required|url',
        'Fecha' => 'required|date',
        'IdConferencia' => 'required',
        'IdFirma' => 'required',
    ];

    public $conferencias;
    public $firmas;

    public function mount()
    {
        $this->conferencias = Conferencia::all();
        $this->firmas = Firma::all();
    }

    public function render()
    {
        $diplomas = Diploma::with(['conferencia', 'firma'])
            ->where('codigo', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'ASC')
            ->paginate(5);

        return view('livewire.Diploma.diplomas', [
            'diplomas' => $diplomas,
            'conferencias' => $this->conferencias,
            'firmas' => $this->firmas,
            'eventos' => $this->searchEventos,
        ]);
    }

    public function updatedInputSearchEvento()
    {
        $query = Evento::query();

        // Busca por nombre del evento si se proporciona
        if (!empty($this->inputSearchEvento)) {
            $query->where('nombreevento', 'like', '%' . $this->inputSearchEvento . '%');
        }

        // Busca por IdEvento si se proporciona
        if (!empty($this->IdEvento)) {
            $query->where('id', $this->IdEvento);
        }

        $this->searchEventos = $query->get();
    }

    public function selectEvento($eventoId)
    {
        $this->IdEvento = $eventoId;
        $evento = Evento::find($eventoId);
        $this->inputSearchEvento = $evento->nombreevento;
        $this->searchEventos = [];
    }


    public function updatedInputSearchConferencia()
    {
        $this->searchConferencias = Conferencia::where('nombre', 'like', '%' . $this->inputSearchConferencia . '%')
            ->get();
    }

    public function updatedInputSearchFirma()
    {
        $this->searchFirmas = Firma::where('nombre', 'like', '%' . $this->inputSearchFirma . '%')
            ->get();
    }

    public function selectConferencia($conferenciaId)
    {
        $this->IdConferencia = $conferenciaId;
        $conferencia = Conferencia::find($conferenciaId);
        $this->inputSearchConferencia = $conferencia->nombre;
        $this->searchConferencias = [];
    }

    public function selectFirma($firmaId)
    {
        $this->IdFirma = $firmaId;
        $firma = Firma::find($firmaId);
        $this->inputSearchFirma = $firma->Nombre;
        $this->searchFirmas = [];
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
        $this->codigo = '';
        $this->URL = '';
        $this->Fecha = '';
        $this->IdConferencia = '';
        $this->IdFirma = '';
        $this->diploma_id = null;
        $this->inputSearchConferencia = '';
        $this->inputSearchFirma = '';
        $this->searchConferencias = [];
        $this->searchFirmas = [];
    }

    public function store()
    {
        $this->validate();

        Diploma::updateOrCreate(['id' => $this->diploma_id], [
            'Codigo' => $this->codigo,
            'URL' => $this->URL,
            'Fecha' => $this->Fecha,
            'IdConferencia' => $this->IdConferencia,
            'IdFirma' => $this->IdFirma,
            'IdEvento' => $this->IdEvento,
        ]);



        session()->flash('message', $this->diploma_id ? 'Diploma actualizado correctamente!' : 'Diploma creado correctamente!');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $diploma = Diploma::findOrFail($id);
        $this->diploma_id = $id;
        $this->codigo = $diploma->codigo;
        $this->URL = $diploma->URL;
        $this->Fecha = $diploma->Fecha;
        $this->IdConferencia = $diploma->IdConferencia;
        $this->IdFirma = $diploma->IdFirma;
        $this->IdEvento = $diploma->IdEvento;

        $evento = Evento::find($this->IdEvento);

        if ($evento) {
            $this->inputSearchEvento = $evento->nombreevento;
        }

        $conferencia = Conferencia::find($this->IdConferencia);
        if ($conferencia) {
            $this->inputSearchConferencia = $conferencia->nombre;
        }

        $firma = Firma::find($this->IdFirma);
        if ($firma) {
            $this->inputSearchFirma = $firma->Nombre;
        }

        $this->openModal();
    }

    public function delete($id)
    {
        Diploma::find($id)->delete();
        session()->flash('message', 'Diploma eliminado correctamente!');
    }
}