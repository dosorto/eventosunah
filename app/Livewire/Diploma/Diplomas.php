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
use Illuminate\Support\Str;

class Diplomas extends Component
{
    use WithPagination, WithFileUploads;

    public// $Codigo,
    $Plantilla,
    $Nombre,
    $Titulo1,
    $NombreFirma1,
    $Firma1,
    $Sello1,
    $Titulo2,
    $NombreFirma2,
    $Firma2,
    $Sello2,
    $Titulo3,
    $NombreFirma3,
    $Firma3,
    $Sello3,
    $diploma_id,
    $search;

    public $isOpen = false;
    public $confirmingDelete = false;
    public $inputSearchConferencia = '';
    public $searchConferencias = [];
    public $IdAEliminar;
    public $inputSearchFirma = '';
    public $searchFirmas = [];


    // public $searchPersonas = [];

    public $inputSearchEvento = '';
    public $searchEventos = [];

    protected $rules = [
        // 'Codigo' => 'required',
        'Plantilla' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Nombre' => 'required',
        'Titulo1' => 'required',
        'NombreFirma1' => 'required',
        'Firma1' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Sello1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Titulo2' => 'nullable',
        'NombreFirma2' => 'nullable',
        'Firma2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Sello2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

    public $conferencias;
    public $firmas;

    public function mount()
    {
        //   $this->conferencias = Conferencia::all();
    }

    public function render()
    {
        $diplomas = Diploma::where('Nombre', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(5);
        // dd($diplomas);
        return view('livewire.Diploma.diplomas', [
            'diplomas' => $diplomas,
            //  'conferencias' => $this->conferencias,
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


    /* public function updatedInputSearchConferencia()
     {
         $this->searchConferencias = Conferencia::where('nombre', 'like', '%' . $this->inputSearchConferencia . '%')
             ->get();
     }
 */
    public function updatedInputSearchFirma()
    {
        $this->searchFirmas = Firma::where('nombre', 'like', '%' . $this->inputSearchFirma . '%')
            ->get();
    }

    /*  public function selectConferencia($conferenciaId)
      {
          $this->IdConferencia = $conferenciaId;
          $conferencia = Conferencia::find($conferenciaId);
          $this->inputSearchConferencia = $conferencia->nombre;
          $this->searchConferencias = [];
      }*/

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
        $this->Plantilla = '';
        $this->Nombre = '';
        $this->Titulo1 = '';
        $this->NombreFirma1 = '';
        $this->Firma1 = '';
        $this->Sello1 = '';
        $this->Titulo2 = '';
        $this->NombreFirma2 = '';
        $this->Firma2 = '';
        $this->Sello2 = '';
      
        $this->diploma_id = null;
        //  $this->inputSearchConferencia = '';
        //   $this->searchConferencias = [];

    }

    public function store()
    {
        $this->validate();
        // guardar las imagenes en el storage
        if ($this->Plantilla) {
            $this->Plantilla = $this->Plantilla->store('public/plantillas');
        } elseif ($this->diploma_id) {
            // Si no se seleccionó un nuevo logo pero se está editando, mantener el logo actual
            $diploma = Diploma::findOrFail($this->diploma_id); // Usa el modelo correcto
            $this->Plantilla = $diploma->Plantilla;
        } else {
            $this->Plantilla = null;
        }

        if ($this->Firma1) {
            $this->Firma1 = $this->Firma1->store('public/firmas');
        }elseif($this->diploma_id){
            // Si no se seleccionó un nuevo logo pero se está editando, mantener el logo actual
            $diploma = Diploma::findOrFail($this->diploma_id);
            $this->Firma1 = $diploma->Firma1;
        } else {
            $this->Firma1 = null;
        }

        if ($this->Firma2) {
            $this->Firma2 = $this->Firma2->store('public/firmas');
        }elseif($this->diploma_id){
            // Si no se seleccionó un nuevo logo pero se está editando, mantener el logo actual
            $diploma = Diploma::findOrFail($this->diploma_id);
            $this->Firma2 = $diploma->Firma2;
        } else {
            $this->Firma2 = null;
        }

       
        if ($this->Sello1) {
            $this->Sello1 = $this->Sello1->store('public/sellos');
        }elseif($this->diploma_id){
            // Si no se seleccionó un nuevo logo pero se está editando, mantener el logo actual
            $diploma = Diploma::findOrFail($this->diploma_id);
            $this->Sello1 = $diploma->Sello1;
        } else {
            $this->Sello1 = null;
        }

        if ($this->Sello2) {
            $this->Sello2 = $this->Sello2->store('public/sellos');
        }elseif($this->diploma_id){
            // Si no se seleccionó un nuevo logo pero se está editando, mantener el logo actual
            $diploma = Diploma::findOrFail($this->diploma_id);
            $this->Sello2 = $diploma->Sello2;
        }
        else {
            $this->Sello2 = null;
        }

        

        Diploma::updateOrCreate(['id' => $this->diploma_id], [
            'Codigo' => $this->generateUniqueCode(),
            'Plantilla' => $this->Plantilla ? str_replace('public/', 'storage/', $this->Plantilla) : null,
            'Nombre' => $this->Nombre,
            'Titulo1' => $this->Titulo1,
            'NombreFirma1' => $this->NombreFirma1,
            'Firma1' => $this->Firma1 ? str_replace('public/', 'storage/', $this->Firma1) : null,
            'Sello1' => $this->Sello1 ? str_replace('public/', 'storage/', $this->Sello1) : null,
            'Titulo2' => $this->Titulo2,
            'NombreFirma2' => $this->NombreFirma2,
            'Firma2' => $this->Firma2 ? str_replace('public/', 'storage/', $this->Firma2) : null,
            'Sello2' => $this->Sello2 ? str_replace('public/', 'storage/', $this->Sello2) : null,
        ]);

        session()->flash('message', $this->diploma_id ? 'Diploma actualizado correctamente!' : 'Diploma creado correctamente!');

        $this->closeModal();
        $this->resetInputFields();
    }

    protected function generateUniqueCode()
    {
        do {
            $code = Str::uuid();
        } while (Diploma::where('codigo', $code)->exists());

        return $code;
    }

    public function edit($id)
    {
        $diploma = Diploma::findOrFail($id);
        $this->diploma_id = $id;
        $this->Nombre= $diploma->Nombre;
        $this->Titulo1 = $diploma->Titulo1;
        $this->NombreFirma1 = $diploma->NombreFirma1;

        $this->Titulo2 = $diploma->Titulo2;
        $this->NombreFirma2 = $diploma->NombreFirma2;


        /*  $conferencia = Conferencia::find($this->IdConferencia);
          if ($conferencia) {
              $this->inputSearchConferencia = $conferencia->nombre;
          }
  */

        $this->openModal();
    }

    public function delete()
    {
        if ($this->confirmingDelete) {
            $diploma = Diploma::find($this->IdAEliminar);

            if (!$diploma) {
                session()->flash('error', 'localidad no encontrada.');
                $this->confirmingDelete = false;
                return;
            }

            $diploma->forceDelete();
            session()->flash('message', 'modalidad eliminada correctamente!');
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $diploma = Diploma::find($id);

        if (!$diploma) {
            session()->flash('error', 'Plantilla no encontrado.');
            return;
        }
        if ($diploma->evento()->exists()) {
            session()->flash('error', 'No se puede eliminar esta plantilla porque está enlazada a uno o más eventos.');
            return;
        }

        $this->IdAEliminar = $id;
        $this->confirmingDelete = true;
    }
}