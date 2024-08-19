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
       // $IdConferencia,
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

    public $inputSearchConferencia = '';
    public $searchConferencias = [];


    public $inputSearchFirma = '';
    public $searchFirmas = [];


    // public $searchPersonas = [];

    public $inputSearchEvento = '';
    public $searchEventos = [];

    protected $rules = [
       // 'Codigo' => 'required',
        'Plantilla' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      //  'IdConferencia' => 'required',
        'Titulo1' => 'required',
        'NombreFirma1' => 'required',
        'Firma1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Sello1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Titulo2' => 'required',
        'NombreFirma2' => 'required',
        'Firma2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Sello2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Titulo3' => 'required',
        'NombreFirma3' => 'required',
        'Firma3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'Sello3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',      
    ];

    public $conferencias;
    public $firmas;

    public function mount()
    {
     //   $this->conferencias = Conferencia::all();
    }

    public function render()
    {
        $diplomas = Diploma::where('Codigo', 'like', '%' . $this->search . '%')
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
      //  $this->IdConferencia = '';
        $this->Titulo1 = '';
        $this->NombreFirma1 = '';
        $this->Firma1 = '';
        $this->Sello1 = '';
        $this->Titulo2 = '';
        $this->NombreFirma2 = '';
        $this->Firma2 = '';
        $this->Sello2 = '';
        $this->Titulo3 = '';
        $this->NombreFirma3 = '';
        $this->Firma3 = '';
        $this->Sello3 = '';
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
        } else {
            $this->Plantilla = null;
        }

        if ($this->Firma1) {
            $this->Firma1 = $this->Firma1->store('public/firmas');
        } else {
            $this->Firma1 = null;
        }

        if ($this->Firma2) {
            $this->Firma2 = $this->Firma2->store('public/firmas');
        } else {
            $this->Firma2 = null;
        }

        if ($this->Firma3) {
            $this->Firma3 = $this->Firma3->store('public/firmas');
        } else {
            $this->Firma3 = null;
        }

        if ($this->Sello1) {
            $this->Sello1 = $this->Sello1->store('public/sellos');
        } else {
            $this->Sello1 = null;
        }

        if ($this->Sello2) {
            $this->Sello2 = $this->Sello2->store('public/sellos');
        } else {
            $this->Sello2 = null;
        }
        
        if ($this->Sello3) {
            $this->Sello3 = $this->Sello3->store('public/sellos');
        } else {
            $this->Sello3 = null;
        }

        /*
            insert into `diplomas` 
            (`Codigo`, 
            `Plantilla`, 
            `Titulo1`, 
            `NombreFirma1`, 
            `Firma1`,
             `Sello1`,
              `Titulo2`,
               `NombreFirma2`, 
               `Firma2`,
                `Sello2`, 
               `Titulo3`,
                `NombreFirma3`, 
                `Firma3`,
                 `Sello3`, 
                 `created_by`,
                  `updated_at`,
                   `created_at`) 
            values
             (2nyNY9TgQb, 
             storage/plantillas/gB7TCrk2MYbBz70LdiQSH7ZGfZg0LbAXFwclR1Lh.jpg, 
             BABYS, 
             BABYS, 
             storage/firmas/HqKV7lH2YBm8EK0hO1PPeqUMeMZlud6TL4NmyUQJ.jpg, 
             storage/sellos/seQ4psWPqlBY8ZulY8E3VKaZCxwVNEAzmJL4wscf.jpg,
              BABYS, 
              BABYS,
               storage/firmas/9TUIhU6IJIjex6lltnk7cdFfZ7wfoKWw7Mwo1BRD.jpg,
               ?, 
               BABYS, 
               BABYS, 
               storage/firmas/43sgAkboeeRlffy6wAWNODsdwc9x5loJRJ1FuhO0.jpg, 
               storage/sellos/hPtnVpFbTg5Oe1oOMDIN1AUxa1pkZMHLuoZhIBIr.jpg, 
               1, 
               2024-08-14 03:37:32, 2024-08-14 03:37:32))

        */
        Diploma::updateOrCreate(['id' => $this->diploma_id], [
            'Codigo' => $this->generateUniqueCode(),
            'Plantilla' =>$this->Plantilla ? str_replace('public/', 'storage/', $this->Plantilla) : null,
         //   'IdConferencia' => $this->IdConferencia,
            'Titulo1' => $this->Titulo1,
            'NombreFirma1' => $this->NombreFirma1,
            'Firma1' => $this->Firma1 ? str_replace('public/', 'storage/', $this->Firma1) : null,
            'Sello1'  =>$this->Sello1 ? str_replace('public/', 'storage/', $this->Sello1) : null,
            'Titulo2' => $this->Titulo2,
            'NombreFirma2' => $this->NombreFirma2,
            'Firma2' =>$this->Firma2 ? str_replace('public/', 'storage/', $this->Firma2) : null,
            'Sello2'  =>$this->Sello2 ? str_replace('public/', 'storage/', $this->Sello2) : null,
            'Titulo3' => $this->Titulo3,
            'NombreFirma3' => $this->NombreFirma3,
            'Firma3'  =>$this->Firma3 ? str_replace('public/', 'storage/', $this->Firma3) : null,
            'Sello3'  =>$this->Sello3 ? str_replace('public/', 'storage/', $this->Sello3) : null,
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
      // $this->Codigo = $diploma->Codigo;
        $this->Plantilla = $diploma->Plantilla;
   //     $this->IdConferencia = $diploma->IdConferencia;
        $this->Titulo1 = $diploma->Titulo1;
        $this->NombreFirma1 = $diploma->NombreFirma1;
        $this->Firma1 = $diploma->Firma1;
        $this->Sello1 = $diploma->Sello1;
        $this->Titulo2 = $diploma->Titulo2;
        $this->NombreFirma2 = $diploma->NombreFirma2;
        $this->Firma2 = $diploma->Firma2;
        $this->Sello2 = $diploma->Sello2;
        $this->Titulo3 = $diploma->Titulo3;
        $this->NombreFirma3 = $diploma->NombreFirma3;
        $this->Firma3 = $diploma->Firma3;
        $this->Sello3 = $diploma->Sello3;


      /*  $conferencia = Conferencia::find($this->IdConferencia);
        if ($conferencia) {
            $this->inputSearchConferencia = $conferencia->nombre;
        }
*/

        $this->openModal();
    }

    public function delete($id)
    {
        Diploma::find($id)->delete();
        session()->flash('message', 'Diploma eliminado correctamente!');
    }
}