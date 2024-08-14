<?php

namespace App\Livewire\Evento;

use App\Models\Modalidad;
use App\Models\Localidad;
use DiplomaComponent;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Evento;
use App\Models\Diploma;

class Eventos extends Component
{
    use WithPagination, WithFileUploads;

    public $logo, $nombreevento, $descripcion, $organizador, $fechainicio, $fechafinal, $horainicio, $horafin, $idmodalidad, $idlocalidad, $IdDiploma, $evento_id, $search;
    public $isOpen = 0;
    public $confirmingDelete = false;
    public $eventoIdAEliminar;
    public $nombreEventoAEliminar;


    public function render()
    {
        $nombreeventos = Evento::with('modalidad', 'localidad', 'diploma')
            ->where('nombreevento', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);

        return view('livewire.Evento.eventos', ['nombreeventos' => $nombreeventos]);
    }

    public $modalidades, $localidades, $diplomas;

    public function mount()
    {
        $this->modalidades = Modalidad::all();
        $this->localidades = Localidad::all();
        $this->diplomas = Diploma::all();
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
    }

    public function store()
    {
        $this->validate([
            'logo' => 'nullable|image|max:1024',
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
        ]);

        $defaultLogo = 'http://www.puertopixel.com/wp-content/uploads/2011/03/Fondos-web-Texturas-web-abtacto-17.jpg';

        if ($this->logo) {
            $this->logo = $this->logo->store('public/eventos');
        } elseif ($this->evento_id) {
            // Si no se seleccionó un nuevo logo pero se está editando, mantener el logo actual
            $nombreevento = Evento::findOrFail($this->evento_id);
            $this->logo = $nombreevento->logo;
        }else{
            $this->logo = $defaultLogo;
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
        ]);

        session()->flash('message', 
            $this->evento_id ? 'Evento Actualizado correctamente!' : 'Evento creado correctamente!');

        $this->closeModal();
        $this->resetInputFields();

        
    }

    public function edit($id)
    {
        $nombreevento = Evento::findOrFail($id);
        $this->evento_id = $id;
       
        $this->nombreevento = $nombreevento->nombreevento;
        $this->descripcion = $nombreevento->descripcion;
        $this->organizador = $nombreevento->organizador;
        $this->fechainicio = $nombreevento->fechainicio;
        $this->fechafinal = $nombreevento->fechafinal;
        $this->horainicio = $nombreevento->horainicio;
        $this->horafin = $nombreevento->horafin;
        $this->idmodalidad = $nombreevento->idmodalidad;
        $this->idlocalidad = $nombreevento->idlocalidad;
        $this->IdDiploma = $nombreevento->IdDiploma;
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
            $this->confirmingDelete = false; // Cierra el modal de confirmación
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
            $this->nombreEventoAEliminar = $evento->nombreevento; // Obtén el nombre del evento
            $this->confirmingDelete = true;
        
    }
/*

*/
    public $showDetails = false;
    public $selectedEvento;
    public function viewDetails($id)
    {
        $this->selectedEvento = Evento::find($id);
        $this->showDetails = true;
    }


    public function closeDetails()
    {
        $this->showDetails = false;
    }

    /*public function detalles($id)
    {
        $evento = Evento::findOrFail($id);
        return view('evento.detalles', compact('evento'));
    }*/
        

}
